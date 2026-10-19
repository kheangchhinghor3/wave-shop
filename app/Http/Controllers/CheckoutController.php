<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $items = CartItem::with('product.category')
            ->where('user_id', Auth::id())
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('checkout.show', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $items = CartItem::where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $order = DB::transaction(function () use ($items, $validated) {
                $total = 0;
                $lines = [];

                foreach ($items as $item) {
                    // Lock the product row so two buyers cannot take the same last unit
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (! $product) {
                        throw new \RuntimeException('A product in your cart is no longer available.');
                    }

                    if ($product->stock < $item->quantity) {
                        throw new \RuntimeException(
                            "Sorry, only {$product->stock} left of {$product->name}. Please update your cart."
                        );
                    }

                    $product->decrement('stock', $item->quantity);

                    $total += $product->price * $item->quantity;

                    $lines[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item->quantity,
                    ];
                }

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'WS-' . now()->format('ymd') . '-' . strtoupper(Str::random(4)),
                    'status' => 'pending',
                    'total' => $total,
                    ...$validated,
                ]);

                $order->items()->createMany($lines);

                // Empty the cart
                CartItem::where('user_id', Auth::id())->delete();

                return $order;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Thank you! Your order has been placed.');
    }
}