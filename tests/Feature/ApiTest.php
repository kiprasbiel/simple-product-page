<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tag;
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

    public function test_api_get_single_product(): void {
        Product::factory()->count(1)->create([
            'SKU' => 'SKU-001'
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/product/SKU-001');

        $response->assertStatus(200);
        $this->assertEquals('SKU-001', $response->original->SKU);
    }

    public function test_api_get_similar_products(): void {
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();
        $product1 = Product::factory()->create();
        $product1->tags()->saveMany([$tag1,$tag2]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/product/' . $product1->id . '/similar');

        $response->assertJsonCount(2);

        $product2 = Product::factory()->create();
        $product2->tags()->save($tag1);

        $response = $this->actingAs($user)->get('/api/product/' . $product2->id . '/similar');

        $similarProductID = json_decode($response->content())[0]->products[0]->id;

        $this->assertEquals($product1->id, $similarProductID);
    }
}
