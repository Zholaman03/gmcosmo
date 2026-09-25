<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('provider')->default('apipay');
            // ApiPay берген invoice/payment ID
            $table->string('external_payment_id')->nullable()->index();

            // Біз ApiPay-ға жіберетін ID: order-123
            $table->string('external_order_id')->nullable()->index();

            $table->decimal('amount', 12, 2);

            $table->string('status')->default('pending');

            // Клиентті ApiPay төлем бетіне апару үшін
            $table->text('payment_url')->nullable();

            $table->timestamp('paid_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
