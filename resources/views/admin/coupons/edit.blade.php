@extends('layouts.app')

@section('title', 'Edit coupon | WAVE SHOP')

@section('content')
    <h1 class="page-title">Edit coupon {{ $coupon->code }}</h1>
    <p class="page-sub">Used {{ $coupon->times_used }} {{ $coupon->times_used === 1 ? 'time' : 'times' }} so far.</p>

    @include('admin._nav')

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="checkout-card admin-form">
        @method('PUT')
        @include('admin.coupons._form')

        <button type="submit" class="btn">Save changes</button>
        <a href="{{ route('admin.coupons.index') }}" class="reset-link">Cancel</a>
    </form>
@endsection