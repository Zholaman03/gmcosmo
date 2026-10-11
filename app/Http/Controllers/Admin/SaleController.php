<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class SaleController extends Controller
{
    //
    public function index()
    {
        $sales = Sale::paginate(10);
        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        return view('admin.sales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'discount_percent' => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        Sale::create($request->all());

        return redirect()->route('admin.sales')->with('success', 'Продажа создана успешно.');
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        return view('admin.sales.edit', compact('sale'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'discount_percent' => 'required|integer|min:1|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $sale = Sale::findOrFail($id);

        $validated['is_active'] = $request->boolean('is_active');

        $sale->update($validated);

        return redirect()
            ->route('admin.sales')
            ->with('success', 'Продажа обновлена успешно.');
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('admin.sales')->with('success', 'Продажа удалена успешно.');
    }
}
