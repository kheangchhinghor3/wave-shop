@extends('layouts.app')

@section('title', 'Admin | WAVE SHOP')

@section('content')
    <h1 class="page-title">Admin dashboard</h1>
    <p class="page-sub">Welcome, {{ Auth::user()->name }}. Here is how the shop is doing.</p>

    @include('admin._nav')

    {{-- Numbers --}}
    <div class="stat-grid">
        <div class="stat-card stat-blue">
            <span class="stat-label">Total orders</span>
            <span class="stat-value">{{ $stats['orders'] }}</span>
        </div>
        <div class="stat-card stat-yellow">
            <span class="stat-label">Pending orders</span>
            <span class="stat-value">{{ $stats['pending'] }}</span>
        </div>
        <div class="stat-card stat-green">
            <span class="stat-label">Revenue</span>
            <span class="stat-value">${{ number_format($stats['revenue'], 2) }}</span>
        </div>
        <div class="stat-card stat-purple">
            <span class="stat-label">Products</span>
            <span class="stat-value">{{ $stats['products'] }}</span>
        </div>
        <div class="stat-card stat-pink">
            <span class="stat-label">Customers</span>
            <span class="stat-value">{{ $stats['customers'] }}</span>
        </div>
    </div>

    <div class="admin-panels">

        {{-- Recent orders --}}
        <section class="admin-panel">
            <h2>Recent orders</h2>

            @forelse ($recentOrders as $order)
                <div class="admin-row">
                    <div>
                        <strong>{{ $order->order_number }}</strong>
                        <div class="admin-sub">
                            {{ $order->user->name ?? 'Deleted user' }} · {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div class="admin-row-right">
                        <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        <strong>${{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            @empty
                <p class="meta">No orders yet.</p>
            @endforelse
        </section>

        {{-- Low stock --}}
        <section class="admin-panel">
            <h2>Low stock</h2>

            @forelse ($lowStock as $product)
                <div class="admin-row">
                    <div>
                        <strong>{{ $product->name }}</strong>
                    </div>
                    <div class="admin-row-right">
                        @if ($product->stock < 1)
                            <span class="stock-out">Sold out</span>
                        @else
                            <span class="stock-low">{{ $product->stock }} left</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="meta">All products have more than 5 in stock.</p>
            @endforelse
        </section>

    </div>
@endsection