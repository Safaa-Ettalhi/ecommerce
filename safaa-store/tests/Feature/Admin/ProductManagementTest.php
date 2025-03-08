<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 29.99,
            'stock' => 100,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 29.99,
        ]);
    }

    public function test_non_admin_cannot_create_product()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 29.99,
            'stock' => 100,
            'category_id' => $category->id,
        ]);

        $response->assertStatus(403);
    }
}

