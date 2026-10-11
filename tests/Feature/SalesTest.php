<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SalesTest extends TestCase
{
    use RefreshDatabase, HasFactory;
    /**
     * A basic feature test example.
     */

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user and authenticate
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true, // Assuming you have an is_admin field to identify admin users
        ]);
        $this->actingAs($user);

        $this->withoutMiddleware(
            \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class
        );
    }
    public function test_sales_page_loads_correctly()
    {


        $response = $this->get('/admin/sales');

        $response->assertStatus(200);
    }
    public function test_create_sale_page_loads_correctly()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/admin/sales/create');
        $response->assertStatus(200);
    }

    public function test_edit_sale_page_loads_correctly()
    {
        $this->withoutExceptionHandling();

        $sale = Sale::factory()->create();
        $response = $this->get('/admin/sales/' . $sale->id . '/edit');
        $response->assertStatus(200);
    }

    public function test_delete_sale_page_loads_correctly()
    {
        $this->withoutExceptionHandling();
        $sale = Sale::factory()->create();
        $response = $this->delete('/admin/sales/' . $sale->id);
        $response->assertStatus(302); // Assuming it redirects after deletion
    }

    public function test_store_sale()
    {
        $response = $this->post('/admin/sales', [
            'product_name' => 'Test Product',
            'discount_percent' => 10,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'is_active' => true,
        ]);

        $response->assertStatus(302); // Assuming it redirects after creation

        $this->assertDatabaseHas('sales', [
            'product_name' => 'Test Product',
            'discount_percent' => 10,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'is_active' => true,
        ]);
    }

    public function test_update_sale()
    {
        $sale = Sale::create([
            'product_name' => 'Old Product',
            'discount_percent' => 5,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'is_active' => true,
        ]);
        $response = $this->put('/admin/sales/' . $sale->id, [
            'product_name' => 'Updated Product',
            'discount_percent' => 20,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'is_active' => true,
        ]);

        $response->assertStatus(302); // Assuming it redirects after update

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'product_name' => 'Updated Product',
            'discount_percent' => 20,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'is_active' => true,
        ]);
    }
}
