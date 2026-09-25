<?php

namespace App\Models;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    //
    protected $fillable = [
        'name',
        'code',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function order(){
        return $this->hasMany(Order::class);
    }
}
