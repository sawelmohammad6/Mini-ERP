<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product']);

        return view('orders.show', compact('order'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::where('is_active', true)->orderBy('name')->get();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'discount'    => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['quantity'] > $product->stock_quantity) {
            return back()
                ->withInput()
                ->with('error', 'Not enough stock available.');
        }

        $discount   = $validated['discount'] ?? 0;
        $subtotal   = $product->price * $validated['quantity'];
        $total      = $subtotal;
        $finalPrice = $subtotal - $discount;

        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'total'       => $total,
            'discount'    => $discount,
            'final_price' => $finalPrice,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity'   => $validated['quantity'],
            'price'      => $product->price,
            'subtotal'   => $subtotal,
        ]);

        $product->decrement('stock_quantity', $validated['quantity']);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $order->load('items');
        $customers = Customer::orderBy('name')->get();
        $products  = Product::where('is_active', true)->orderBy('name')->get();

        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'discount'    => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $discount = $validated['discount'] ?? 0;
        $subtotal = $product->price * $validated['quantity'];
        $total    = $subtotal;
        $finalPrice = $subtotal - $discount;

        $oldItem = $order->items()->first();
        if ($oldItem && $oldItem->product_id != $validated['product_id']) {
            $oldProduct = Product::find($oldItem->product_id);
            if ($oldProduct) {
                $oldProduct->increment('stock_quantity', $oldItem->quantity);
            }
        }

        $order->update([
            'customer_id' => $validated['customer_id'],
            'total'       => $total,
            'discount'    => $discount,
            'final_price' => $finalPrice,
        ]);

        $order->items()->delete();
        $order->items()->create([
            'product_id' => $product->id,
            'quantity'   => $validated['quantity'],
            'price'      => $product->price,
            'subtotal'   => $subtotal,
        ]);

        $product->decrement('stock_quantity', $validated['quantity']);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->items()->delete();
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
