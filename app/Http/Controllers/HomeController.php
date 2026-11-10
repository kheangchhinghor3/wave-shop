<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Limited drops that are still running and in stock, ending soonest first
        $limited = Product::with('category', 'images')
            ->where('is_limited', true)
            ->where('stock', '>', 0)
            ->where(function ($query) {
                $query->whereNull('drop_ends_at')
                      ->orWhere('drop_ends_at', '>', now());
            })
            ->orderByRaw('drop_ends_at is null')
            ->orderBy('drop_ends_at')
            ->take(4)
            ->get();

        // Featured products (if none are marked, show the newest ones instead)
        $featured = Product::with('category', 'images')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $featuredTitle = 'Featured products';

        if ($featured->isEmpty()) {
            $featured = Product::with('category', 'images')->latest()->take(8)->get();
            $featuredTitle = 'New arrivals';
        }

        return view('home', [
            'limited'       => $limited,
            'featured'      => $featured,
            'featuredTitle' => $featuredTitle,
            'categories'    => Category::orderBy('name')->get(),
            'wishlistIds'   => $this->wishlistIds(),
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