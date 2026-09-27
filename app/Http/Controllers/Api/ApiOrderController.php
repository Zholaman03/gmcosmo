<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\DeliveryMethod;
use App\Services\ApiPayService;
use Illuminate\Support\Facades\DB;

class ApiOrderController extends Controller
{
    public function order(Request $request, ApiPayService $apiPay)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'products' => 'required|array|min:1',
            'delivery_method_code' => 'required|string|exists:delivery_methods,code',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($request) {

            $totalPrice = 0;
            $totalQuantity = 0;

            $deliveryMethod = DeliveryMethod::query()
                ->where('code', $request->delivery_method_code)
                ->where('is_active', true)
                ->firstOrFail();

            $order = Order::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'delivery_method_id' => $deliveryMethod->id,
                'comment'=>$request->comment,
                'total_price' => 0,
                'total_quantity' => 0,
                'paid_at' => null,
            ]);

            foreach ($request->products as $item) {

                $product = Product::query()
                    ->whereKey($item['id'])
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = $item['quantity'];
                if ($product->stock < $quantity) {
                    abort(422, "Недостаточно товара: {$product->name}");
                }
                $price = $product->price;

                $totalPrice += $price * $quantity;
                $totalQuantity += $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

             
            }

            $order->update([
                'total_price' => $totalPrice,
                'total_quantity' => $totalQuantity,
            ]);

            $order->payments()->create([
                'provider' => 'apipay',
                'amount' => $totalPrice,
                'status' => 'pending',
                'external_order_id' => 'order-' . $order->id,
                'external_payment_id' => null,
                'payment_url' => null,
                'paid_at' => null,
            ]);

            return $order;
        });

        // ApiPay-ға QR/invoice жасау
        $payment = $apiPay->createQr(
            $order->total_price,
            'Заказ №' . $order->id,
            'order-' . $order->id
        );

        return response()->json([

            'success' => true,
            'order_id' => $order->id,
            'address' => $order->address,
            'total_price' => $order->totalprice,
            'delivery_method' => $order->deliveryMethod?->name,
            'payment_url' => $payment['qr_token_url'] ?? null,
            'qr_image_url' => $payment['qr_image_url'] ?? null,
            'expires_at' => $payment['qr_expires_at'] ?? null,
        ]);
    }
}
