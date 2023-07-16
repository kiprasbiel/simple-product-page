<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_get_all_products(): void
    {
        Product::factory()->count(10)->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/products');

        $response->assertStatus(200);

        $response->assertJsonCount(10);
    }
}
