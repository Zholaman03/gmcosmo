<?php

use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\CheckPayment;
use App\Http\Controllers\Api\ApiPayWebhookController;
use Illuminate\Support\Facades\Route;


Route::post('/order', [ApiOrderController::class, 'order']);

Route::post('/payment/apipay/webhook', [ApiPayWebhookController::class, 'handle']);

//check pay
Route::get('/payment/status/{order}', [CheckPayment::class, 'check']);