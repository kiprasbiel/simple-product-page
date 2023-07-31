<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
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
        $product = Cache::remember("SKU_$sku", now()->addMinutes(10),
            fn() =>
            Product::without('stocks')->where('SKU', $sku)->first()
        );
        return $product->loadMissing('stocks');
    }

    public function similarProducts(Product $product): Collection {
        return $product->similarProducts();
    }
}
