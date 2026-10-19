<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Product $product)
    {
        if ($product->stock < 1) {
            return back()->with('error', 'Sorry, this product is sold out.');
        }

        $item = CartItem::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        $current = $item->exists ? $item->quantity : 0;

        // Never allow more than the stock we have
        if ($current >= $product->stock) {
            return back()->with('error', "You already have all {$product->stock} available in your cart.");
        }

        $item->quantity = $current + 1;
        $item->save();

        return back()->with('success', "{$product->name} was added to your cart.");
    }
}