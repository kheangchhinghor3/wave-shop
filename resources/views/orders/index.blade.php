@extends('layouts.app')

@section('title', 'My Orders | WAVE SHOP')

@section('content')
    <h1 class="page-title">My orders</h1>

    @if ($orders->count() > 0)
        <p class="page-sub">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} so far</p>

        <div class="order-list">
            @foreach ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="order-card">
                    <div>
                        <div class="order-card-number">{{ $order->order_number }}</div>
                        <div class="order-card-meta">
                            {{ $order->created_at->format('d M Y, H:i') }}
                            &middot;
                            {{ $order->items_sum_quantity }} {{ Str::plural('item', $order->items_sum_quantity) }}
                        </div>
                    </div>

                    <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>

                    <div class="order-card-total">${{ number_format($order->total, 2) }}</div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-box">
            <p>You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn">Start shopping</a>
        </div>
    @endif
@endsection