<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Sale;

class Category extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    //
    protected $fillable = ['name'];
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class);
    }
}
