<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index() {
        return ProductsResource::collection(Product::without(['tags', 'content', 'stocks'])->paginate(8));
    }

    public function show(string $sku) {
        $product = Cache::remember("SKU_$sku", now()->addMinutes(10),
            fn() =>
            Product::without('stocks')->where('SKU', $sku)->first()
        );
        return $product->loadMissing('stocks');
    }

    public function similarProducts(Product $product): Collection {
        return Cache::remember("similar_SKU_$product->SKU", now()->addMinutes(10),
            fn() => $product->similarProducts());
    }
}
