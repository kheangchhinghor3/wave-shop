<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by name, description or collection
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('collection', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by price range
        if (is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if (is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->get();
        $categories = Category::orderBy('name')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'wishlistIds' => $this->wishlistIds(),
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'wishlistIds' => $this->wishlistIds(),
        ]);
    }

    // IDs of the products the logged-in user has saved (empty for guests)
    private function wishlistIds(): array
    {
        if (! Auth::check()) {
            return [];
        }

        return WishlistItem::where('user_id', Auth::id())
            ->pluck('product_id')
            ->all();
    }
}