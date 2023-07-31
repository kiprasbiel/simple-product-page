<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    public function index(): Collection{
        return Product::with(['content', 'tags', 'stocks'])->get();
    }

    public function show(string $sku): Product | string {
        return Product::where('SKU', $sku)->first() ?: '{}';
    }

    public function similarProducts(Product $product): Collection {
        return $product->similarProducts();
    }
}
