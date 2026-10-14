@extends('layouts.app')

@section('title', 'Products | WAVE SHOP')

@section('content')
    <h1 class="page-title">Shop</h1>
    <p class="page-sub">{{ $products->count() }} products found</p>

    <div class="grid">
        @foreach ($products as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="card {{ $product->stock > 0 ? '' : 'card-soldout' }}">

                <div class="card-thumb">
                    @if ($product->is_limited)
                        <span class="badge">LIMITED</span>
                    @endif
                    <span class="thumb-icon">🌊</span>
                </div>

                <div class="card-body">
                    <div class="category">{{ $product->category->name }}</div>
                    <div class="name">{{ $product->name }}</div>
                    <div class="price">${{ number_format($product->price, 2) }}</div>

                    @if ($product->stock > 0)
                        <p class="meta">In stock: {{ $product->stock }}</p>
                    @else
                        <p class="soldout">SOLD OUT</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endsection