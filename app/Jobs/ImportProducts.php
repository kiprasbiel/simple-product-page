<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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

    private function execute(array $products): void {
        foreach ($products as $product) {
            $newProduct = Product::create([
                'SKU' => $product['sku'],
                'size' => $product['size'],
                'photo_url' => $product['photo'],
                'updated_at' => $product['updated_at']
            ]);

            $newProduct->content()->create([
                'description' => $product['description']
            ]);

            $newProduct->tags()->createMany(
                $product['tags']
            );
        }
    }
}
