<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_admin_page(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::factory()->create();

        $this->actingAs($admin);

        $response = $this->post('/admin/products', [
            'name' => 'Test Product',
            'category_id' => $category->id,
            'price' => 5000,
            'barcode' => 'TEST-123',
            'stock' => 10,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 5000,
        ]);
    }
}
