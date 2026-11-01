@extends('layouts.app')

@section('title', 'Admin coupons | WAVE SHOP')

@section('content')
    <h1 class="page-title">Manage coupons</h1>
    <p class="page-sub">{{ $coupons->count() }} coupons.</p>

    @include('admin._nav')

    <div class="admin-toolbar">
        <a href="{{ route('admin.coupons.create') }}" class="btn">+ Add coupon</a>
    </div>

    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min order</th>
                    <th>Used</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    @php
                        if (! $coupon->is_active) {
                            $state = 'inactive'; $label = 'Inactive';
                        } elseif ($coupon->expires_at && $coupon->expires_at->isPast()) {
                            $state = 'expired'; $label = 'Expired';
                        } elseif ($coupon->usage_limit !== null && $coupon->times_used >= $coupon->usage_limit) {
                            $state = 'usedup'; $label = 'Used up';
                        } else {
                            $state = 'active'; $label = 'Active';
                        }

                        $discountText = $coupon->type === 'percent'
                            ? rtrim(rtrim(number_format((float) $coupon->value, 2), '0'), '.') . '%'
                            : '$' . number_format((float) $coupon->value, 2);
                    @endphp

                    <tr>
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>{{ $discountText }} off</td>
                        <td>{{ $coupon->min_order > 0 ? '$' . number_format((float) $coupon->min_order, 2) : '-' }}</td>
                        <td>
                            {{ $coupon->times_used }}
                            {{ $coupon->usage_limit !== null ? '/ ' . $coupon->usage_limit : '' }}
                        </td>
                        <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y, H:i') : 'Never' }}</td>
                        <td><span class="status status-{{ $state }}">{{ $label }}</span></td>
                        <td class="admin-actions">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-small">Edit</a>

                            <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-small">{{ $coupon->is_active ? 'Turn off' : 'Turn on' }}</button>
                            </form>

                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                  onsubmit="return confirm('Delete coupon {{ $coupon->code }}? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-small-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No coupons yet. Click "+ Add coupon" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection