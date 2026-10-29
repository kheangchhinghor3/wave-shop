<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
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

        $subtotal = $this->subtotalOf($items);

        // Coupon saved in the session (if any)
        $coupon = null;
        $discount = 0;
        $code = session('coupon_code');

        if ($code) {
            $coupon = Coupon::where('code', $code)->first();
            $problem = $coupon
                ? $coupon->problemFor($subtotal)
                : 'This coupon is no longer available.';

            if ($problem) {
                // Example: the cart dropped below the minimum order
                session()->forget('coupon_code');
                session()->now('error', $problem);
                $coupon = null;
            } else {
                $discount = $coupon->discountFor($subtotal);
            }
        }

        return view('cart.index', [
            'items'    => $items,
            'subtotal' => $subtotal,
            'coupon'   => $coupon,
            'discount' => $discount,
            'total'    => $subtotal - $discount,
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

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $items = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return back()->with('error', 'That coupon code is not valid.');
        }

        $problem = $coupon->problemFor($this->subtotalOf($items));

        if ($problem) {
            return back()->with('error', $problem);
        }

        session(['coupon_code' => $coupon->code]);

        return back()->with('success', "Coupon {$coupon->code} applied!");
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');

        return back()->with('success', 'Coupon removed.');
    }

        private function subtotalOf(\Illuminate\Support\Collection $items): float
    {
        return (float) $items->sum(fn ($item) => $item->product->price * $item->quantity);
    }
}