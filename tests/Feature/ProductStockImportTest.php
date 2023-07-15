<?php

namespace Tests\Feature;

use App\Jobs\ImportProductStocks;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductStockImportTest extends TestCase
{

    public function setUp(): void {
        parent::setUp();
        Storage::fake('product_stock_import');

    }

    /**
     * @throws ValidationException
     */
    public function test_product_stock_import(): void
    {
        $productsSKU = Product::factory()->count(3)->create()->pluck('SKU');

        $stockArray = [];
        foreach ($productsSKU as $sku) {
            $stockArray[] = [
                'sku' => $sku,
                'stock' => fake()->numberBetween(1, 100),
                'city' => fake()->city(),
            ];
        }

        $stockJson = json_encode($stockArray);

        Storage::disk('product_stock_import')->put('stocks-test.json', $stockJson);

        (new ImportProductStocks())->handle();

        $this->assertDatabaseCount('product_stocks', 3);

    }
}
