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
        //
        
    }
}