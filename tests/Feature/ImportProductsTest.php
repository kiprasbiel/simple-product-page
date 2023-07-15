<?php

namespace Tests\Feature;

use App\Jobs\ImportProducts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportProductsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
        Storage::fake('product_import');
        Storage::disk('product_import')->put('products-test.json', '[{"sku": "KF-004", "description": "Poisoning by hydantoin derivatives, self-harm, init", "size": "2XL", "photo": "http://dummyimage.com/125x100.png/cc0000/ffffff", "tags": [], "updated_at": "2022-04-29"}, {"sku": "KF-025", "description": "Displ transverse fx shaft of unsp tibia, 7thJ", "size": "M", "photo": "http://dummyimage.com/218x100.png/5fa2dd/ffffff", "tags": [], "updated_at": "2022-02-04"}, {"sku": "KF-041", "description": "Nonspecific urethritis", "size": "3XL", "photo": "http://dummyimage.com/130x100.png/5fa2dd/ffffff", "tags": [{"title": "Cookley"}], "updated_at": "2022-02-01"}]');
    }

    /**
     * Testing if correct amount of DB records are created.
     */
    public function test_import_products(): void
    {
        (new ImportProducts())->handle();

        $this->assertDatabaseCount('products', 3);
        $this->assertDatabaseCount('product_contents', 3);
        $this->assertDatabaseCount('product_tags', 1);
    }

    public function test_import_command(): void {
        $this->artisan('app:import-products')->assertExitCode(0)
            ->expectsOutputToContain('Product import job added');
    }
}
