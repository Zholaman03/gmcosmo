<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\DeliveryMethod;

class Order extends Model
{
    //
    protected $fillable = [
        'name',
        'phone',
        'total_price',
        'delivery_method_id',
        'address',
        'comment',
        'status',
        'total_quantity',
        'payment_status',
        'payment_id',
        'paid_at'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
