<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index()
    {
        $query = Order::with(['customer', 'items.product']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(config('erp.pagination_size'));

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
        try {
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
        } catch (\Exception $e) {
            Log::error('Order creation failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

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
        try {
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
        } catch (\Exception $e) {
            Log::error('Order update failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        try {
            $this->orderService->deleteOrder($order);
        } catch (\Exception $e) {
            Log::error('Order deletion failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
