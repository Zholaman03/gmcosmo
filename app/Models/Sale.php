<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Category;

class Sale extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    //
    protected $fillable = [
        'product_name',
        'discount_percent',
        'start_date',
        'end_date',
        'is_active',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
