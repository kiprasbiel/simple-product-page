<?php

namespace Tests\Feature;

use App\Jobs\ImportProducts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportProductsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Testing if correct amount of DB records are created.
     */
    public function test_import_products(): void
    {
        (new ImportProducts())->handle();

        $this->assertDatabaseCount('products', 97);
        $this->assertDatabaseCount('product_contents', 97);
        $this->assertDatabaseCount('product_tags', 48);
    }
}
