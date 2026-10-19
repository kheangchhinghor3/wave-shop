@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' | WAVE SHOP')

@section('content')
    <h1 class="page-title">Order {{ $order->order_number }}</h1>
    <p class="page-sub">
        Placed on {{ $order->created_at->format('d M Y, H:i') }}
        &middot;
        <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
    </p>

    <div class="cart-layout">

        {{-- Items --}}
        <div class="cart-list">
            @foreach ($order->items as $item)
                <div class="order-row">
                    <div>
                        <div class="cart-name">{{ $item->product_name }}</div>
                        <div class="cart-unit">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</div>
                    </div>
                    <div class="cart-line">${{ number_format($item->price * $item->quantity, 2) }}</div>
                </div>
            @endforeach
        </div>

        {{-- Delivery and total --}}
        <aside class="cart-summary">
            <h2>Delivery</h2>
            <p class="delivery-text">
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_phone }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}
            </p>

            @if ($order->notes)
                <p class="summary-note">Notes: {{ $order->notes }}</p>
            @endif

            <div class="summary-row summary-total">
                <span>Total</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>

            <a href="{{ route('products.index') }}" class="reset-link">Continue shopping</a>
        </aside>

    </div>
@endsection