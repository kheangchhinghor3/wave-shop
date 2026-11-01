@extends('layouts.app')

@section('title', 'Admin orders | WAVE SHOP')

@section('content')
    <h1 class="page-title">Manage orders</h1>
    <p class="page-sub">{{ $counts->sum() }} orders in total.</p>

    @include('admin._nav')

    {{-- Status tabs --}}
    <div class="filter-tabs">
        <a href="{{ route('admin.orders.index') }}" class="{{ $status ? '' : 'active' }}">
            All <span>{{ $counts->sum() }}</span>
        </a>
        @foreach ($statuses as $s)
            <a href="{{ route('admin.orders.index', ['status' => $s]) }}" class="{{ $status === $s ? 'active' : '' }}">
                {{ ucfirst($s) }} <span>{{ $counts[$s] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="admin-search">
        @if ($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <input type="text" name="search" value="{{ $search }}" placeholder="Search order number or customer name">
        <button type="submit" class="btn-small">Search</button>
        @if ($search)
            <a href="{{ route('admin.orders.index', array_filter(['status' => $status])) }}" class="reset-link">Clear</a>
        @endif
    </form>

    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"><strong>{{ $order->order_number }}</strong></a>
                        </td>
                        <td>
                            {{ $order->shipping_name }}
                            <div class="admin-sub">{{ $order->user->email ?? 'Deleted user' }}</div>
                        </td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td>{{ $order->items_sum_quantity ?? 0 }}</td>
                        <td>
                            <strong>${{ number_format($order->total, 2) }}</strong>
                            @if ($order->discount > 0)
                                <div class="admin-sub">coupon {{ $order->coupon_code }}</div>
                            @endif
                        </td>
                        <td><span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td class="admin-actions">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-small">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection