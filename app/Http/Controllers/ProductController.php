<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(): Collection{
        return Cache::remember('products', now()->addMinutes(10) , fn() => Product::without(['tags', 'content', 'stocks'])->get());
    }

    public function show(string $sku): Product | string {
        return Product::where('SKU', $sku)->first() ?: '{}';
    }

    public function similarProducts(Product $product): Collection {
        return $product->similarProducts();
    }
}
