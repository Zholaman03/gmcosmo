<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row): ?Product
    {
        $barcode = $this->normalizeBarcode($row['barcode']);
        // Категорияны табамыз немесе жаңасын құрамыз
        $category = Category::firstOrCreate([
            'name' => trim($row['category']),
        ]);

        // Barcode арқылы бұрынғы өнімді іздейміз
        $product = Product::where(
            'barcode',
            trim($barcode)
        )->first();

        if ($product) {

            // Өнім бұрыннан бар болса — UPDATE
            $product->update([
                'category_id' => $category->id,
                'name'        => $row['name'],
                'price'       => $row['price'],
                'stock'       => $row['stock'],
                'description' => $row['description'] ?? null,
                'status'      => 'active',

                // image-ді әдейі өзгертпейміз
            ]);

            // ToModel болғандықтан Product қайтару керек
            return null;
        }

        // Barcode жоқ болса — жаңа өнім құрамыз
        return new Product([
            'barcode'     => trim($barcode),
            'category_id' => $category->id,
            'name'        => $row['name'],
            'price'       => $row['price'],
            'stock'       => $row['stock'],
            'description' => $row['description'] ?? null,
            'image'       => null,
            'status'      => 'active',
        ]);
    }

    private function normalizeBarcode($barcode): string
    {
        $barcode = trim((string) $barcode);

        // 2,11E+12 → 2.11E+12
        $barcode = str_replace(',', '.', $barcode);

        // Егер scientific notation болса
        if (stripos($barcode, 'e') !== false) {
            $barcode = sprintf('%.0f', (float) $barcode);
        }

        return $barcode;
    }
}