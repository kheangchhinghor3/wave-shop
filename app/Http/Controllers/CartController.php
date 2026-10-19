<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = CartItem::with('product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

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

    public function update(Request $request, CartItem $item)
    {
        // A user may only change their own cart
        abort_if($item->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $quantity = $validated['quantity'];

        // Quantity 0 means remove
        if ($quantity < 1) {
            $item->delete();

            return back()->with('success', 'Item removed from your cart.');
        }

        if ($quantity > $item->product->stock) {
            return back()->with('error', "Only {$item->product->stock} available for {$item->product->name}.");
        }

        $item->update(['quantity' => $quantity]);

        return back();
    }

    public function remove(CartItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);

        $item->delete();

        return back()->with('success', 'Item removed from your cart.');
    }
}