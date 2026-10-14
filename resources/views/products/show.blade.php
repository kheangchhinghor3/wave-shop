@extends('layouts.app')

@section('title', $product->name . ' - WAVE SHOP')

@section('content')
    <a href="{{ route('products.index') }}" class="back-link">&larr; Back to all products</a>

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

                <button type="button" class="btn">Add to Cart</button>
            @else
                <p class="soldout">SOLD OUT</p>
            @endif
        </div>
    </div>

    @if ($relatedProducts->count() > 0)
        <h2 class="section-title">Related products</h2>
        <div class="grid">
            @foreach ($relatedProducts as $related)
                <a href="{{ route('products.show', $related->slug) }}"
                   class="card {{ $related->stock > 0 ? '' : 'card-soldout' }}">

                    <div class="card-thumb">
                        @if ($related->is_limited)
                            <span class="badge">LIMITED</span>
                        @endif
                        <span class="thumb-icon">🌊</span>
                    </div>

                    <div class="card-body">
                        <div class="category">{{ $related->category->name }}</div>
                        <div class="name">{{ $related->name }}</div>
                        <div class="price">${{ number_format($related->price, 2) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection