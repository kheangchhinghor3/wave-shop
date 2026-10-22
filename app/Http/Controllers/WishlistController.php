<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $products = WishlistItem::with('product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->pluck('product');

        return view('wishlist.index', [
            'products' => $products,
            'wishlistIds' => $products->pluck('id')->all(),
        ]);
    }

    // One button does both: save if new, remove if already saved
    public function toggle(Product $product)
    {
        $item = WishlistItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->delete();

            return back()->with('success', "{$product->name} was removed from your wishlist.");
        }

        WishlistItem::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', "{$product->name} was saved to your wishlist.");
    }
}