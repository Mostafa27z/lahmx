<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Str;

class OrderRepository
{
    public function createOrderFromCart(Cart $cart, array $customerData): Order
    {
        $subtotal = $cart->total;
        $deliveryFee = 15.00; // Flat shipping rate in SAR
        $total = $subtotal + $deliveryFee;

        // Generate unique order number (e.g. LHMX-20260615-XYZ12)
        $orderNumber = 'LHMX-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $order = Order::create([
            'user_id' => $customerData['user_id'] ?? null,
            'order_number' => $orderNumber,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'payment_method' => $customerData['payment_method'],
            'payment_status' => 'pending',
            'customer_name' => $customerData['customer_name'],
            'phone' => $customerData['phone'],
            'city' => $customerData['city'],
            'address' => $customerData['address'],
            'notes' => $customerData['notes'] ?? null,
        ]);

        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'total' => $cartItem->subtotal,
                'options' => $cartItem->options,
            ]);

            // Deduct stock
            $product = $cartItem->product;
            if ($product) {
                $product->decrement('stock_quantity', $cartItem->quantity);
            }
        }

        return $order;
    }

    public function findById(int $orderId): ?Order
    {
        return Order::with(['items.product', 'payment'])->find($orderId);
    }

    public function getOrdersForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Order::where('user_id', $userId)->latest()->get();
    }
}
