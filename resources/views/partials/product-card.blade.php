@php
    $saved = in_array($product->id, $wishlistIds ?? []);
@endphp

<div class="card-wrap">
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

    {{-- Heart button (sits on top of the card, outside the link) --}}
    @auth
        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="heart-form">
            @csrf
            <button type="submit" class="heart-btn {{ $saved ? 'is-active' : '' }}"
                    aria-label="{{ $saved ? 'Remove from wishlist' : 'Add to wishlist' }}">
                {{ $saved ? '♥' : '♡' }}
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="heart-btn" aria-label="Login to save to wishlist">♡</a>
    @endauth
</div>