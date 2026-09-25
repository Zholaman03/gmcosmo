<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|extensions:xlsx,xls,csv|max:10240',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $file = $request->file('file');

                // 1. Барлық қазіргі өнімдерді inactive қыламыз
                Product::query()->update([
                    'status' => 'inactive'
                ]);

                // 2. Excel импорттаймыз
                Excel::import(
                    new ProductsImport,
                    $file
                );
            });

            return back()->with(
                'success',
                'Товарлар сәтті импортталды!'
            );
        } catch (\Throwable $e) {
            report($e);
            return back()->with(
                'error',
                'Excel файлын импорттау кезінде қате пайда болды.'
            );
        }
    }

    public function showImportForm()
    {
        return view('admin.import');
    }
}
