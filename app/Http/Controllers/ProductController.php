<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class ProductController extends Controller
{
    public function index(Request $request)
    {

        $products = Product::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(50)
            ->withQueryString();


        $categories = \App\Models\Category::all();

        $DeliveryMethods = \App\Models\DeliveryMethod::where('is_active', true)->get();

        // Get the total count of products

        return view('pages.product', compact('products', 'categories', 'DeliveryMethods'));
    }

    public function filterByCategory($categoryId)
    {

        $products = Product::where('category_id', $categoryId)->with('category')->latest()->paginate(50);
        $categories = \App\Models\Category::all();

        $DeliveryMethods = \App\Models\DeliveryMethod::where('is_active', true)->get();

        return view('pages.product', compact('products', 'categories', 'DeliveryMethods'));
    }
}
