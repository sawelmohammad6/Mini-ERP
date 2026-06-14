<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])
            ->latest()
            ->paginate(config('erp.pagination_size'));

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
        $products  = Product::with('orderItems')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        if (!$this->orderService->validateStock($product, $validated['quantity'])) {
            return back()
                ->withInput()
                ->with('error', 'Not enough stock available.');
        }

        $totals = $this->orderService->calculateTotals(
            $product->price,
            $validated['quantity'],
            $validated['discount'] ?? 0
        );

        $this->orderService->createOrder(
            [
                'customer_id' => $validated['customer_id'],
                'total'       => $totals['total'],
                'discount'    => $validated['discount'] ?? 0,
                'final_price' => $totals['final_price'],
                'status'      => OrderStatus::Pending,
            ],
            [
                'product_id' => $product->id,
                'quantity'   => $validated['quantity'],
                'price'      => $product->price,
                'subtotal'   => $totals['subtotal'],
            ],
            $product
        );

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $order->load('items');
        $customers = Customer::orderBy('name')->get();
        $products  = Product::with('orderItems')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        if (!$this->orderService->validateStock($product, $validated['quantity'])) {
            return back()
                ->withInput()
                ->with('error', 'Not enough stock available.');
        }

        $totals = $this->orderService->calculateTotals(
            $product->price,
            $validated['quantity'],
            $validated['discount'] ?? 0
        );

        $this->orderService->updateOrder(
            $order,
            [
                'customer_id' => $validated['customer_id'],
                'total'       => $totals['total'],
                'discount'    => $validated['discount'] ?? 0,
                'final_price' => $totals['final_price'],
            ],
            [
                'product_id' => $product->id,
                'quantity'   => $validated['quantity'],
                'price'      => $product->price,
                'subtotal'   => $totals['subtotal'],
            ],
            $product
        );

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->orderService->deleteOrder($order);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
