<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    //
    public function import(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {


                $request->validate([
                    'file' => 'required|file|extensions:xlsx,xls,csv|max:10240',
                ]);

                $file = $request->file('file');

                Product::query()->delete(); // Барлық бұрынғы өнімдерді жою
                Category::query()->delete(); // Барлық бұрынғы категорияларды жою
                // Excel файлын импорттау
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
