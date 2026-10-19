@extends('layouts.app')

@section('title', 'Checkout | WAVE SHOP')

@section('content')
    <h1 class="page-title">Checkout</h1>
    <p class="page-sub">Enter your delivery details to place your order.</p>

    <div class="cart-layout">

        {{-- Delivery form --}}
        <form action="{{ route('checkout.store') }}" method="POST" class="checkout-card">
            @csrf
            <h2>Delivery details</h2>

            <div class="form-group">
                <label for="shipping_name">Full name</label>
                <input type="text" id="shipping_name" name="shipping_name"
                       value="{{ old('shipping_name', Auth::user()->name) }}" required>
                @error('shipping_name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="shipping_phone">Phone number</label>
                <input type="text" id="shipping_phone" name="shipping_phone"
                       value="{{ old('shipping_phone') }}" required>
                @error('shipping_phone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="shipping_address">Address</label>
                <input type="text" id="shipping_address" name="shipping_address"
                       value="{{ old('shipping_address') }}" required>
                @error('shipping_address')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="shipping_city">City</label>
                <input type="text" id="shipping_city" name="shipping_city"
                       value="{{ old('shipping_city') }}" required>
                @error('shipping_city')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes">Notes (optional)</label>
                <textarea id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-full">Place order</button>
        </form>

        {{-- Order summary --}}
        <aside class="cart-summary">
            <h2>Your order</h2>

            @foreach ($items as $item)
                <div class="summary-row">
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach

            <div class="summary-row summary-total">
                <span>Total</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>

            <p class="summary-note">Payment: pay on delivery.</p>
            <a href="{{ route('cart.index') }}" class="reset-link">Back to cart</a>
        </aside>

    </div>
@endsection