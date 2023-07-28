<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_response_products() {
        $response = $this->get('/api/products');
        $this->testIfUnauthenticated($response);
    }

    public function test_unauthenticated_response_single_product() {
        $response = $this->get('/api/product/SKU-001');
        $this->testIfUnauthenticated($response);
    }

    public function test_unauthenticated_response_similar_products() {
        $response = $this->get('/api/product/SKU-001/similar');
        $this->testIfUnauthenticated($response);
    }

    private function testIfUnauthenticated($response): void {
        $response->assertJson([
            'message' => 'Unauthenticated.',
            'code' => 403
        ]);
    }

    public function test_can_register_new_user() {
        $response = $this->post('/api/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJson([
            'message' => 'Registration successful.',
        ]);

        $response->assertJsonStructure([
            'message',
            'token'
        ]);


        $response = $this->get('/api/products', [
            'Authorization' => 'Bearer ' . $response['token']
        ]);

        $response->assertStatus(200);
    }

    public function test_can_login() {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertJson([
            'message' => 'Login successful.'
        ]);

        $response->assertJsonStructure([
            'message',
            'token'
        ]);

        $response = $this->get('/api/products', [
            'Authorization' => 'Bearer ' . $response['token']
        ]);

        $response->assertStatus(200);
    }

    public function test_login_with_wrong_credentials() {
        $response = $this->post('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password'
        ]);

        $response->assertJson([
            'message' => 'Login failed. Check your credentials.'
        ], true);
    }
}
