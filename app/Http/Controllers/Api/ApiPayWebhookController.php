<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiPayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('ApiPay webhook received', [
            'body' => $request->all(),
        ]);

        $data = $request->all();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid webhook data',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        $event = $data['event'] ?? null;

        if ($event !== 'invoice.status_changed') {
            return response()->json([
                'success' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Invoice
        |--------------------------------------------------------------------------
        */

        $invoice = $data['invoice'] ?? null;

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice data not found',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment identification
        |--------------------------------------------------------------------------
        */

        $externalOrderId = $invoice['external_order_id'] ?? null;

        if (!$externalOrderId) {
            return response()->json([
                'success' => false,
                'message' => 'external_order_id not found',
            ], 400);
        }

        $payment = Payment::where(
            'external_order_id',
            $externalOrderId
        )->first();

        if (!$payment) {
            Log::warning('ApiPay payment not found', [
                'external_order_id' => $externalOrderId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment status
        |--------------------------------------------------------------------------
        */

        $status = $invoice['status'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Paid
        |--------------------------------------------------------------------------
        */

        if ($status === 'paid') {
            if ($payment->status === 'paid') {
                return response()->json(['success' => true,]);
            }

            DB::transaction(function () use ($payment, $invoice) {
                $order = $payment->order;

                foreach ($order->items as $item) {
                    $product = $item->product()->lockForUpdate()->first();
                    if (!$product) {
                        throw new \Exception("Product not found: {$item->product_id}");
                    }
                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Недостаточно товара: {$product->name}");
                    }
                    $product->decrement('stock', $item->quantity);
                }
                $payment->update([
                    'external_payment_id' => $invoice['id'] ?? null,
                    'status' => 'paid',
                    'paid_at' => $invoice['paid_at'] ?? now(),
                ]);

                $payment->order->update([
                    'paid_at' => $invoice['paid_at'] ?? now(),

                    'payment_status' => 'paid'
                ]);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
        ]);
    }
}
