@extends('layouts.app')

@section('title', 'WAVE SHOP | K-pop inspired goods')

@section('content')

    {{-- Banner --}}
    <section class="hero">
        <div class="hero-text">
            <span class="hero-tag">K-POP INSPIRED GOODS</span>
            <h1>Ride the wave with <span>WAVE SHOP</span></h1>
            <p>Discover K-pop inspired goods, from albums to tech. Grab the limited drops before the countdown ends.</p>

            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn">Shop now</a>
                @if ($limited->count() > 0)
                    <a href="#limited" class="btn btn-wish">See limited drops</a>
                @endif
            </div>
        </div>

        <div class="hero-art" aria-hidden="true">🌊</div>
    </section>

    {{-- Categories --}}
    @if ($categories->count() > 0)
        <div class="chip-row">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="chip">{{ $category->name }}</a>
            @endforeach
        </div>
    @endif

    {{-- Limited drops --}}
    @if ($limited->count() > 0)
        <section id="limited" class="home-section">
            <div class="section-head">
                <h2 class="section-title">Limited drops</h2>
                <a href="{{ route('products.index') }}" class="reset-link">View all products</a>
            </div>

            <div class="grid">
                @foreach ($limited as $product)
                    @include('partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistIds])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured products --}}
    @if ($featured->count() > 0)
        <section class="home-section">
            <div class="section-head">
                <h2 class="section-title">{{ $featuredTitle }}</h2>
                <a href="{{ route('products.index') }}" class="reset-link">View all products</a>
            </div>

            <div class="grid">
                @foreach ($featured as $product)
                    @include('partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistIds])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Why shop here --}}
    <section class="perks">
        <div class="perk">
            <span class="perk-icon">🚚</span>
            <strong>Pay on delivery</strong>
            <span>No payment needed until your order arrives.</span>
        </div>
        <div class="perk">
            <span class="perk-icon">🎟️</span>
            <strong>Coupon codes</strong>
            <span>Enter a code at the cart to save on your order.</span>
        </div>
        <div class="perk">
            <span class="perk-icon">⭐</span>
            <strong>Fan reviews</strong>
            <span>Read what other fans think before you buy.</span>
        </div>
    </section>

@endsection