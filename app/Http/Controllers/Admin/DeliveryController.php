<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryMethod;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveryMethods = DeliveryMethod::all();
        return view('admin.delivery', compact('deliveryMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:delivery_methods,code',
            'is_active' => 'boolean',
        ]);

        DeliveryMethod::create($request->only('name', 'code', 'is_active'));

        return redirect()->back()->with('success', 'Доставка способа доставки успешно добавлена.');
    }

    public function toogle($id)
    {
        $deliveryMethod = DeliveryMethod::findOrFail($id);
        $deliveryMethod->is_active = !$deliveryMethod->is_active;
        $deliveryMethod->save();

        return redirect()->back()->with('success', 'Способ доставки успешно обновлен.');
    }


}
