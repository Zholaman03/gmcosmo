<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
class Payment extends Model
{
    //
    protected $fillable = [
        'order_id',
        'provider',
        'external_payment_id',
        'external_order_id',
        'amount',
        'status',
        'payment_url',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
