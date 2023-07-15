<?php

namespace Tests\Feature;

use App\Jobs\ImportProductStocks;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductStockImportTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
        Storage::fake('product_stock_import');

    }

    /**
     * @throws ValidationException
     */
    public function test_product_stock_import(): void
    {
        $this->createProductsAndGenerateStock();

        $this->assertDatabaseCount('product_stocks', 3);

    }

    /**
     * @throws ValidationException
     */
    public function test_product_stock_update(): void {
        $productsStocks = $this->createProductsAndGenerateStock();

        $stockArray = [];
        foreach ($productsStocks as $stock) {
            $stockArray[] = [
                'sku' => $stock['sku'],
                'stock' => 101,
                'city' => $stock['city'],
            ];
        }

        $stockJson = json_encode($stockArray);

        Storage::disk('product_stock_import')->put('stocks-test.json', $stockJson);

        (new ImportProductStocks())->handle();

        $stockCounts = array_column(ProductStock::select('stock')->get()->toArray(), 'stock');

        $this->assertEquals([101, 101, 101], $stockCounts);
    }

    /**
     * @return array
     * @throws ValidationException
     */
    private function createProductsAndGenerateStock(): array {
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

        return $stockArray;
    }
}
