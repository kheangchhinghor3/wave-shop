@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' | WAVE SHOP')

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="back-link">&larr; Back to orders</a>

    <h1 class="page-title">Order {{ $order->order_number }}</h1>
    <p class="page-sub">
        Placed {{ $order->created_at->format('d M Y, H:i') }}
        by {{ $order->user->name ?? 'a deleted user' }}
    </p>

    @include('admin._nav')

    <div class="admin-detail">

        {{-- Left: items and totals --}}
        <section class="admin-panel">
            <h2>Items</h2>

            @foreach ($order->items as $item)
                <div class="admin-row">
                    <div>
                        <strong>{{ $item->product_name }}</strong>
                        <div class="admin-sub">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</div>
                    </div>
                    <strong>${{ number_format($item->price * $item->quantity, 2) }}</strong>
                </div>
            @endforeach

            @if ($order->discount > 0)
                <div class="admin-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->total + $order->discount, 2) }}</span>
                </div>
                <div class="admin-row summary-discount">
                    <span>Coupon {{ $order->coupon_code }}</span>
                    <span>-${{ number_format($order->discount, 2) }}</span>
                </div>
            @endif

            <div class="admin-row admin-total">
                <span>Total</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>

            <p class="meta">Payment: pay on delivery.</p>
        </section>

        {{-- Right: status and delivery --}}
        <div class="admin-side">

            <section class="admin-panel">
                <h2>Status</h2>

                <p>
                    Current status:
                    <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </p>

                @if ($order->status === 'cancelled')
                    <p class="meta">This order was cancelled, so its status can't be changed.</p>
                @else
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="status-form"
                          onsubmit="return this.status.value !== 'cancelled' || confirm('Cancel this order? The items will be put back in stock.');">
                        @csrf
                        @method('PATCH')

                        <select name="status" aria-label="New status">
                            @foreach ($statuses as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn">Update status</button>
                    </form>
                @endif
            </section>

            <section class="admin-panel">
                <h2>Delivery details</h2>

                <div class="invoice-field">
                    <span class="invoice-label">Name</span>
                    <span>{{ $order->shipping_name }}</span>
                </div>
                <div class="invoice-field">
                    <span class="invoice-label">Phone</span>
                    <span>{{ $order->shipping_phone }}</span>
                </div>
                <div class="invoice-field">
                    <span class="invoice-label">Address</span>
                    <span>{{ $order->shipping_address }}</span>
                </div>
                <div class="invoice-field">
                    <span class="invoice-label">City</span>
                    <span>{{ $order->shipping_city }}</span>
                </div>

                @if ($order->notes)
                    <div class="invoice-field">
                        <span class="invoice-label">Notes</span>
                        <span>{{ $order->notes }}</span>
                    </div>
                @endif
            </section>

        </div>
    </div>
@endsection