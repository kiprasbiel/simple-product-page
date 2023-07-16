<?php

namespace Tests\Feature;

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
}
