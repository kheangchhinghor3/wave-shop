@extends('layouts.app')

@section('title', 'My Wishlist | WAVE SHOP')

@section('content')
    <h1 class="page-title">My wishlist</h1>

    @if ($products->count() > 0)
        <p class="page-sub">{{ $products->count() }} saved {{ Str::plural('product', $products->count()) }}</p>

        <div class="grid">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistIds])
            @endforeach
        </div>
    @else
        <div class="empty-box">
            <p>Your wishlist is empty. Tap the ♡ on any product to save it here.</p>
            <a href="{{ route('products.index') }}" class="btn">Browse products</a>
        </div>
    @endif
@endsection