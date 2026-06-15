<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Process mock payment for the order
     *
     * @param Order $order
     * @param string $method
     * @return array [success => bool, transaction_id => string, message => string]
     */
    public function processMockPayment(Order $order, string $method): array
    {
        $transactionId = 'TXN-' . strtoupper($method) . '-' . Str::random(10);
        
        // Mocking successful response. In production this would connect to the provider API.
        $success = true; 
        
        // Let's create a payment record
        Payment::create([
            'order_id' => $order->id,
            'method' => $method,
            'transaction_id' => $transactionId,
            'amount' => $order->total,
            'status' => $success ? 'success' : 'failed',
        ]);

        if ($success) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed', // update to confirmed on successful payment
            ]);
            
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'message' => 'تمت عملية الدفع بنجاح عبر ' . strtoupper($method),
            ];
        }

        $order->update([
            'payment_status' => 'failed',
        ]);

        return [
            'success' => false,
            'transaction_id' => $transactionId,
            'message' => 'فشلت عملية الدفع، الرجاء المحاولة مرة أخرى.',
        ];
    }
}
