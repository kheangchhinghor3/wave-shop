<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return view('products.index', ['products' => $products]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}