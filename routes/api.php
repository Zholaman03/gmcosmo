<?php

use App\Http\Controllers\Api\ApiOrderController;
use Illuminate\Support\Facades\Route;


Route::post('/order', [ApiOrderController::class, 'order']);
