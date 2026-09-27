<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CheckPayment extends Controller
{
    //

    public function check(Order $order)
    {
        $payment = $order->payments()->latest()->first();

        return response()->json([
            'status' => $payment?->status ?? 'pending',
        ]);
    }
}
