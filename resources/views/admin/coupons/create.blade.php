@extends('layouts.app')

@section('title', 'Add coupon | WAVE SHOP')

@section('content')
    <h1 class="page-title">Add coupon</h1>

    @include('admin._nav')

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="checkout-card admin-form">
        @include('admin.coupons._form')

        <button type="submit" class="btn">Create coupon</button>
        <a href="{{ route('admin.coupons.index') }}" class="reset-link">Cancel</a>
    </form>
@endsection