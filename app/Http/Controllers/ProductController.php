<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    public function index(){

        $products = \App\Models\Product::with('category')->get();

        $categories = \App\Models\Category::all();

        return view('pages.product', compact('products', 'categories'));
    }

    public function filterByCategory($categoryId)
    {
      
        $products = Product::where('category_id', $categoryId)->get();
        $categories = \App\Models\Category::all();

        return view('pages.product', compact('products', 'categories'));
    }

    public function order(Request $request)
    {
       // Debugging line to inspect the request data
       $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:30',

        'products' => 'required|array|min:1',

        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    DB::transaction(function () use ($request) {

        $totalPrice = 0;
        $totalQuantity = 0;

        $order = Order::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'total_price' => 0,
            'total_quantity' => 0,
            'status' => 'pending',
        ]);

        foreach ($request->products as $item) {

            $product = Product::findOrFail($item['id']);

            $quantity = $item['quantity'];
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
    });

    return response()->json([
        'success' => true,
        'message' => 'Заказ успешно оформлен',
    ]);
    }
   
}
