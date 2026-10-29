@extends('layouts.app')

@section('title', 'My Cart | WAVE SHOP')

@section('content')
    <h1 class="page-title">My cart</h1>

    @if ($items->count() > 0)
        <p class="page-sub">{{ $items->sum('quantity') }} items in your cart</p>

        <div class="cart-layout">

            {{-- Items --}}
            <div class="cart-list">
                @foreach ($items as $item)
                    <div class="cart-row">
                        <div class="cart-thumb">🌊</div>

                        <div class="cart-info">
                            <div class="category">{{ $item->product->category->name }}</div>
                            <a href="{{ route('products.show', $item->product->slug) }}" class="cart-name">
                                {{ $item->product->name }}
                            </a>
                            <div class="cart-unit">${{ number_format($item->product->price, 2) }} each</div>
                        </div>

                        <div class="qty">
                            <form action="{{ route('cart.update', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                <button type="submit" class="qty-btn" aria-label="Decrease quantity">−</button>
                            </form>

                            <span class="qty-num">{{ $item->quantity }}</span>

                            <form action="{{ route('cart.update', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                <button type="submit" class="qty-btn" aria-label="Increase quantity">+</button>
                            </form>
                        </div>

                        <div class="cart-line">
                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                        </div>

                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cart-remove" aria-label="Remove item">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <aside class="cart-summary">
                <h2>Order summary</h2>

                <div class="summary-row">
                    <span>Items</span>
                    <span>{{ $items->sum('quantity') }}</span>
                </div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>

                {{-- Coupon --}}
                @if ($coupon)
                    <div class="summary-row summary-discount">
                        <span>Coupon {{ $coupon->code }}</span>
                        <span>-${{ number_format($discount, 2) }}</span>
                    </div>

                    <form action="{{ route('coupon.remove') }}" method="POST" class="coupon-remove">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-link-danger">Remove coupon</button>
                    </form>
                @else
                    <form action="{{ route('coupon.apply') }}" method="POST" class="coupon-form">
                        @csrf
                        <input type="text" name="code" placeholder="Coupon code" autocomplete="off" required>
                        <button type="submit" class="btn-coupon">Apply</button>
                    </form>
                @endif

                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout.show') }}" class="btn btn-full">Checkout</a>
                <a href="{{ route('products.index') }}" class="reset-link">Continue shopping</a>
            </aside>

        </div>
    @else
        <div class="empty-box">
            <p>Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="btn">Start shopping</a>
        </div>
    @endif
@endsection