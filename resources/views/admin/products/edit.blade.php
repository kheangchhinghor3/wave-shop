@extends('layouts.app')

@section('title', 'Edit product | WAVE SHOP')

@section('content')
    <h1 class="page-title">Edit product</h1>

    @include('admin._nav')

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="checkout-card admin-form">
        @method('PUT')
        @include('admin.products._form')

        <button type="submit" class="btn">Save changes</button>
        <a href="{{ route('admin.products.index') }}" class="reset-link">Cancel</a>
    </form>

    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="admin-form-delete"
          onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-small btn-small-danger">Delete this product</button>
    </form>
@endsection