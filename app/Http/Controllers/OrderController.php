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
        $orders = Order::with('customer')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();

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

        $product  = Product::findOrFail($validated['product_id']);
        $discount = $validated['discount'] ?? 0;
        $subtotal = $product->price * $validated['quantity'];
        $total    = $subtotal;
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

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $order->load('items');
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();

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

        $product  = Product::findOrFail($validated['product_id']);
        $discount = $validated['discount'] ?? 0;
        $subtotal = $product->price * $validated['quantity'];
        $total    = $subtotal;
        $finalPrice = $subtotal - $discount;

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

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
