@extends('layouts.app')

@section('title', 'Add product | WAVE SHOP')

@section('content')
    <h1 class="page-title">Add product</h1>

    @include('admin._nav')

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="checkout-card admin-form">
        @include('admin.products._form')

        <button type="submit" class="btn">Add product</button>
        <a href="{{ route('admin.products.index') }}" class="reset-link">Cancel</a>
    </form>
@endsection