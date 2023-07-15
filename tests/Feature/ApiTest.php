<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_get_all_products(): void
    {
        Product::factory()->count(10)->create();

        $response = $this->get('/api/products');

        $response->assertStatus(200);

        $response->assertJsonCount(10);
    }
}
