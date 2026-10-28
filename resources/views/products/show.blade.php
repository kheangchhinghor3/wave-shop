@extends('layouts.app')

@section('title', $product->name . ' - WAVE SHOP')

@section('content')
    <a href="{{ route('products.index') }}" class="back-link">&larr; Back to all products</a>

    @php
        $saved = in_array($product->id, $wishlistIds ?? []);

        // Reviews for this product
        $reviews  = $product->reviews()->with('user')->latest()->get();
        $myReview = auth()->check() ? $reviews->firstWhere('user_id', auth()->id()) : null;
        $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : null;
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

            @if ($avgRating)
                <div class="rating-summary">
                    <span class="stars">{{ str_repeat('★', (int) round($avgRating)) }}{{ str_repeat('☆', 5 - (int) round($avgRating)) }}</span>
                    <span>{{ $avgRating }} ({{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }})</span>
                </div>
            @endif

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

    <section class="reviews" id="reviews">
        <h2 class="section-title">Reviews</h2>

        @auth
            <form action="{{ route('reviews.store', $product->slug) }}#reviews" method="POST" class="review-form">
                @csrf
                <h3>{{ $myReview ? 'Edit your review' : 'Write a review' }}</h3>
                <p class="review-label">Your rating</p>

                <div class="star-input">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required
                            {{ (int) old('rating', $myReview->rating ?? 0) === $i ? 'checked' : '' }}>
                        <label for="star{{ $i }}" title="{{ $i }} stars">★</label>
                    @endfor
                </div>
                @error('rating')
                    <div class="form-error">Please choose a star rating.</div>
                @enderror

                <p class="review-label">Your comment (optional)</p>
                <textarea name="comment" rows="3" placeholder="Share what you think about this product...">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                @error('comment')
                    <div class="form-error">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn">{{ $myReview ? 'Update review' : 'Submit review' }}</button>
            </form>

            @if ($myReview)
                <form action="{{ route('reviews.destroy', $product->slug) }}#reviews" method="POST" class="review-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-link-danger">Delete my review</button>
                </form>
            @endif
        @else
            <p class="meta"><a href="{{ route('login') }}">Login</a> to write a review.</p>
        @endauth

        @forelse ($reviews as $review)
            <div class="review-item">
                <div class="review-top">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
                </div>
                @if ($review->comment)
                    <p>{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p class="meta">No reviews yet. Be the first to review this product!</p>
        @endforelse
    </section>

    @if ($relatedProducts->count() > 0)
        <h2 class="section-title">Related products</h2>
        <div class="grid">
            @foreach ($relatedProducts as $related)
                @include('partials.product-card', ['product' => $related, 'wishlistIds' => $wishlistIds])
            @endforeach
        </div>
    @endif
@endsection