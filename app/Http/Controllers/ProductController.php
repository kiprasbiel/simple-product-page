<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection{
        return Cache::remember('products', now()->addMinutes(10),
            fn() =>
            ProductsResource::collection(Product::without(['tags', 'content', 'stocks'])->get())
        );
    }

    public function show(string $sku) {
        return Cache::remember("SKU_$sku", now()->addMinutes(10),
            fn() =>
            new SingleProductResource(Product::where('SKU', $sku)->first())
        );
    }

    public function similarProducts(Product $product): Collection {
        return $product->similarProducts();
    }
}
