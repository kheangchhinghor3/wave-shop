@php
    $saved = in_array($product->id, $wishlistIds ?? []);
    $cover = $product->images->first();
@endphp

<article class="pcard">
    <a href="{{ route('products.show', $product->slug) }}" class="pcard-media">
        @if ($cover)
            <img src="{{ asset($cover->path) }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <span class="pcard-emoji">🌊</span>
        @endif

        @if ($product->is_limited)
            <span class="pcard-badge">LIMITED DROP</span>
        @endif
    </a>

    @auth
        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="pcard-heart-form">
            @csrf
            <button type="submit" class="pcard-heart {{ $saved ? 'is-active' : '' }}"
                    aria-label="{{ $saved ? 'Remove from wishlist' : 'Add to wishlist' }}">
                {{ $saved ? '♥' : '♡' }}
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="pcard-heart pcard-heart-link" aria-label="Login to save">♡</a>
    @endauth

    <div class="pcard-body">
        <div class="category">{{ $product->category->name }}</div>

        <a href="{{ route('products.show', $product->slug) }}" class="pcard-name">{{ $product->name }}</a>

        <div class="pcard-price">${{ number_format($product->price, 2) }}</div>

        <div class="pcard-actions">
            @if ($product->stock > 0)
                @auth
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn pcard-add">Add to Cart</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn pcard-add">Login to add</a>
                @endauth
            @else
                <span class="pcard-soldout">SOLD OUT</span>
            @endif
        </div>
    </div>
</article>