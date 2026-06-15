<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected CartRepository $cartRepository;
    protected OrderRepository $orderRepository;
    protected PaymentService $paymentService;

    public function __construct(
        CartRepository $cartRepository,
        OrderRepository $orderRepository,
        PaymentService $paymentService
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentService = $paymentService;
    }

    /**
     * Place an order from the active cart.
     */
    public function placeOrder(array $customerData): Order
    {
        $cart = $this->cartRepository->getOrCreateCart();

        if ($cart->items->isEmpty()) {
            throw new Exception('السلة فارغة حالياً.');
        }

        // Validate stock before checkout
        foreach ($cart->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                throw new Exception("الكمية المطلوبة من المنتج ({$item->product->name}) غير متوفرة في المخزون. المتوفر: {$item->product->stock_quantity}");
            }
        }

        return DB::transaction(function () use ($cart, $customerData) {
            // Create Order
            $order = $this->orderRepository->createOrderFromCart($cart, $customerData);

            // Process Mock Payment
            $paymentResult = $this->paymentService->processMockPayment($order, $customerData['payment_method']);

            if (!$paymentResult['success']) {
                throw new Exception($paymentResult['message']);
            }

            // Clear the Cart
            $this->cartRepository->clearCart();

            return $order;
        });
    }
}
