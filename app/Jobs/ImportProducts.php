<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ImportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileArray = Storage::disk('product_import')->files();
        foreach ($fileArray as $file) {
            if(pathinfo($file)['extension'] !== 'json') continue;

            $products = Storage::disk('product_import')->json($file);
            $this->execute($products);
        }
    }

    /**
     * @throws ValidationException
     */
    private function execute(array $products): void {
        foreach ($products as $product) {
            $validator = Validator::make($product, [
                'sku' => 'required|string',
                'size' => 'required|string',
                'photo' => 'required|url',
                'description' => 'required|string',
                'updated_at' => 'date',
                'tags'  => 'array',
            ]);

            if ($validator->fails()) {
                Log::warning('Product importer row validator failed. Failed: ' .
                    var_export($validator->failed(), true)
                );
                Log::warning('Failed row: ' . var_export($product, true));
                continue;
            }

            $validated = $validator->validated();

            $newProduct = Product::create([
                'SKU' => $validated['sku'],
                'size' => $validated['size'],
                'photo_url' => $validated['photo'],
                'updated_at' => $validated['updated_at']
            ]);

            $newProduct->content()->create([
                'description' => $validated['description']
            ]);

            $newProduct->tags()->createMany(
                $validated['tags']
            );
        }
    }
}
