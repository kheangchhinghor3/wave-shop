@extends('layouts.app')

@section('title', 'Admin products | WAVE SHOP')

@section('content')
    <h1 class="page-title">Manage products</h1>
    <p class="page-sub">{{ $products->count() }} products in the shop.</p>

    @include('admin._nav')

    <div class="admin-toolbar">
        <a href="{{ route('admin.products.create') }}" class="btn">+ Add product</a>
    </div>

    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Tags</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php $cover = $product->images->first(); @endphp
                    <tr>
                        <td>
                            @if ($cover)
                                <img src="{{ asset($cover->path) }}" alt="" class="admin-thumb">
                                <span class="photo-count">{{ $product->images->count() }} photos</span>
                            @else
                                <span class="admin-thumb admin-thumb-empty">🌊</span>
                                <span class="photo-count photo-count-warn">no photos</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                        </td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            @if ($product->stock < 1)
                                <span class="stock-out">Sold out</span>
                            @elseif ($product->stock <= 5)
                                <span class="stock-low">{{ $product->stock }}</span>
                            @else
                                {{ $product->stock }}
                            @endif
                        </td>
                        <td>
                            @if ($product->is_limited)
                                <span class="tag">Limited</span>
                            @endif
                            @if ($product->is_featured)
                                <span class="tag">Featured</span>
                            @endif
                        </td>
                        <td class="admin-actions">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-small">Edit</a>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-small-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection