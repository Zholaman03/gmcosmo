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
        $category = Category::firstOrCreate([
            'name' => $row['category'],
        ]);

        return new Product([
            'category_id' => $category->id,
            'name' => $row['name'],
            'price' => $row['price'],
            'description' => $row['description'] ?? null,
            'image' => $row['image'] ?? null,
        ]);
    }
}