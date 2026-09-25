<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('products', 'quantity')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('quantity');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'quantity')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->unsignedInteger('quantity')->default(0);
            });
        }
    }
};
