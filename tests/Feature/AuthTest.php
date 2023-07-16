<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_unauthenticated_response() {
        $response = $this->get('/api/products');
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
}
