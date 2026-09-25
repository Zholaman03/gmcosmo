<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\ApiPayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Order $order, ApiPayService $apiPay)
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'apipay',
            'amount' => $order->total_price,
            'external_order_id' => 'order-' . $order->id,
            'status' => 'pending',
        ]);
        
        $response = $apiPay->createQr(
            $payment->amount,
            'Заказ №' . $order->id,
            $payment->external_order_id
        );

        $payment->update([
            'external_payment_id' => $response['id'] ?? null,
            'payment_url' => $response['qr_token_url'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'payment_url' => $response['qr_token_url'] ?? null,
            'qr_image_url' => $response['qr_image_url'] ?? null,
            'expires_at' => $response['qr_expires_at'] ?? null,
        ]);
    }
}