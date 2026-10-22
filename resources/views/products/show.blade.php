@extends('layouts.app')

@section('title', $product->name . ' - WAVE SHOP')

@section('content')
    <a href="{{ route('products.index') }}" class="back-link">&larr; Back to all products</a>

    @php
        $saved = in_array($product->id, $wishlistIds ?? []);
    @endphp

    <div class="detail">
        <div class="detail-image">
            <span class="thumb-icon">🌊</span>
        </div>

        <div class="detail-info">
            @if ($product->is_limited)
                <span class="badge-inline">LIMITED DROP</span>
            @endif

            <div class="category">{{ $product->category->name }}</div>
            <h1>{{ $product->name }}</h1>
            <div class="price">${{ number_format($product->price, 2) }}</div>

            <p class="detail-desc">{{ $product->description }}</p>

            @if ($product->collection)
                <p class="meta">Collection: {{ $product->collection }}</p>
            @endif

            @if ($product->stock > 0)
                <p class="meta">
                    @if ($product->is_limited)
                        Only {{ $product->stock }} left
                    @else
                        In stock: {{ $product->stock }}
                    @endif
                </p>
            @else
                <p class="soldout">SOLD OUT</p>
            @endif

            <div class="detail-actions">
                @if ($product->stock > 0)
                    @auth
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn">Add to Cart</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn">Login to add to cart</a>
                    @endauth
                @endif

                @auth
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-wish {{ $saved ? 'is-active' : '' }}">
                            {{ $saved ? '♥ Saved to wishlist' : '♡ Add to wishlist' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-wish">♡ Add to wishlist</a>
                @endauth
            </div>
        </div>
    </div>

    @if ($relatedProducts->count() > 0)
        <h2 class="section-title">Related products</h2>
        <div class="grid">
            @foreach ($relatedProducts as $related)
                @include('partials.product-card', ['product' => $related, 'wishlistIds' => $wishlistIds])
            @endforeach
        </div>
    @endif
@endsection