<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * OrderService
 *
 * Why Service Layer?
 * ------------------
 * Encapsulates order business logic (stock management, pricing calculations)
 * so controllers remain thin and focused on HTTP concerns (request/response).
 * The service is testable in isolation without HTTP dependencies.
 *
 * Responsibilities:
 * - Stock validation and deduction/restoration.
 * - Subtotal, total, discount, and final price calculations.
 * - Order item synchronization.
 *
 * Multi-step write operations use DB::transaction to ensure atomicity:
 * if any step fails, all prior changes are rolled back.
 */
class OrderService
{
    /**
     * Validate that enough stock exists for the requested quantity.
     */
    public function validateStock(Product $product, int $quantity): bool
    {
        return $quantity <= $product->stock_quantity;
    }

    /**
     * Deduct stock when an order is created or updated.
     */
    public function deductStock(Product $product, int $quantity): void
    {
        $product->decrement('stock_quantity', $quantity);
    }

    /**
     * Restore stock when an order item is removed or changed.
     */
    public function restoreStock(Product $product, int $quantity): void
    {
        $product->increment('stock_quantity', $quantity);
    }

    /**
     * Calculate order financials.
     *
     * @return array{subtotal: float, total: float, final_price: float}
     */
    public function calculateTotals(float $price, int $quantity, float $discount = 0): array
    {
        $subtotal   = $price * $quantity;
        $total      = $subtotal;
        $finalPrice = max(0, $subtotal - $discount);

        return [
            'subtotal'    => $subtotal,
            'total'       => $total,
            'final_price' => $finalPrice,
        ];
    }

    /**
     * Create an order with one item, deduct stock, and return the order.
     */
    public function createOrder(array $orderData, array $itemData, Product $product): Order
    {
        return DB::transaction(function () use ($orderData, $itemData, $product) {
            $order = Order::create($orderData);

            $order->items()->create($itemData);

            $this->deductStock($product, $itemData['quantity']);

            return $order;
        });
    }

    /**
     * Update an existing order: restore old stock, update order/item, deduct new stock.
     */
    public function updateOrder(Order $order, array $orderData, array $itemData, Product $newProduct): Order
    {
        return DB::transaction(function () use ($order, $orderData, $itemData, $newProduct) {
            $oldItem = $order->items()->first();

            if ($oldItem && $oldItem->product_id !== $itemData['product_id']) {
                $oldProduct = Product::find($oldItem->product_id);
                if ($oldProduct) {
                    $this->restoreStock($oldProduct, $oldItem->quantity);
                }
            }

            $order->update($orderData);

            $order->items()->delete();
            $order->items()->create($itemData);

            $this->deductStock($newProduct, $itemData['quantity']);

            return $order;
        });
    }

    /**
     * Delete an order and restore stock for all its items.
     */
    public function deleteOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $this->restoreStock($product, $item->quantity);
                }
            }

            $order->items()->delete();
            $order->delete();
        });
    }
}
