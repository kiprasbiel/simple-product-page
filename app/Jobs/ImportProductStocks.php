<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ImportProductStocks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * @throws ValidationException
     */
    public function handle(): void
    {
        $fileArray = Storage::disk('product_stock_import')->files();
        foreach ($fileArray as $file) {
            if(pathinfo($file)['extension'] !== 'json') continue;

            $products = Storage::disk('product_stock_import')->json($file);
            $this->execute($products);
        }
    }

    /**
     * @throws ValidationException
     */
    public function execute($products): void {
        foreach ($products as $product) {
            $validatedProduct = $this->validateData($product);

            if(!$validatedProduct) continue;

            $product = Product::where('SKU', $validatedProduct['sku'])->first();
            if (!$product) {
                Log::warning('During product Stock import couldn\'t find product with SKU: ' . $validatedProduct['sku']);
                continue;
            }

            $product->stocks()
                ->save(new ProductStock([
                    'stock' => $validatedProduct['stock'],
                    'location' => $validatedProduct['city'],
                ]));
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateData($product): false|array {
        $validator = Validator::make($product, [
            'sku' => 'required|string',
            'stock' => 'required|numeric',
            'city' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::warning('Product stock importer row validator failed. Failed: ' .
                var_export($validator->failed(), true)
            );
            Log::warning('Failed row: ' . var_export($product, true));
            return false;
        }

        return  $validator->validated();
    }
}
