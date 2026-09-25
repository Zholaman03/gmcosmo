<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiPayService
{
    public function createQr(
        int|float $amount,
        string $description,
        string $externalOrderId
    ): array {
        $response = Http::withHeaders([
            'X-API-Key' => config('services.apipay.api_key'),
            'Content-Type' => 'application/json',
        ])->connectTimeout(5)->timeout(15)->post('https://api.apipay.kz/api/v1/invoices/qr', [
            'amount' => $amount,
            'description' => $description,
            'external_order_id' => $externalOrderId,
        ]);

        $response->throw();

        return $response->json();
    }
}