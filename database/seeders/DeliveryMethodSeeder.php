<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \App\Models\DeliveryMethod::create([
            'name' => 'Самовывоз',
            'code' => 'pickup_delivery',
            'is_active' => true,
        ]);
        \App\Models\DeliveryMethod::create([
            'name' => 'Доставка курьером',
            'code' => 'courier_delivery',
            'is_active' => true,
        ]);
        \App\Models\DeliveryMethod::create([
            'name' => 'Почта',
            'code' => 'post_delivery',
            'is_active' => true,
        ]);
    }
}
