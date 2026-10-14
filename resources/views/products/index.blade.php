@extends('layouts.app')

@section('title', 'Products | WAVE SHOP')

@section('content')
    <h1 class="page-title">Shop</h1>

    {{-- Live search bar (belongs to the filter form below via form="shop-form") --}}
    <div class="search-bar">
        <span class="search-icon">🔍</span>
        <input type="text" id="search-input" name="search" form="shop-form"
               value="{{ request('search') }}"
               placeholder="Start typing to search products..." autocomplete="off">
        <button type="button" id="search-clear" class="search-clear-btn"
                aria-label="Clear search" {{ request('search') ? '' : 'hidden' }}>✕</button>
    </div>

    <p class="page-sub" id="result-info">
        {{ $products->count() }} products found
        @if (request('search'))
            for "{{ request('search') }}"
        @endif
    </p>

    <div class="shop-layout">

        {{-- Sidebar --}}
        <aside class="sidebar">
            <form id="shop-form" action="{{ route('products.index') }}" method="GET">

                <div class="filter-group">
                    <span class="filter-title">Category</span>

                    <label class="radio-row">
                        <input type="radio" name="category" value=""
                               {{ request('category') ? '' : 'checked' }}>
                        All categories
                    </label>

                    @foreach ($categories as $category)
                        <label class="radio-row">
                            <input type="radio" name="category" value="{{ $category->id }}"
                                   {{ request('category') == $category->id ? 'checked' : '' }}>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>

                <div class="filter-group">
                    <span class="filter-title">Price ($)</span>
                    <div class="price-row">
                        <input type="number" name="min_price" min="0" step="0.01"
                               value="{{ request('min_price') }}" placeholder="Min">
                        <input type="number" name="max_price" min="0" step="0.01"
                               value="{{ request('max_price') }}" placeholder="Max">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-title" for="sort">Sort by</label>
                    <select id="sort" name="sort">
                        <option value="">Newest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: low to high</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: high to low</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    </select>
                </div>

                <a href="{{ route('products.index') }}" class="reset-link">Reset all</a>
            </form>
        </aside>

        {{-- Products --}}
        <div class="shop-products" id="shop-products">
            @if ($products->count() > 0)
                <div class="grid">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}"
                           class="card {{ $product->stock > 0 ? '' : 'card-soldout' }}">

                            <div class="card-thumb">
                                @if ($product->is_limited)
                                    <span class="badge">LIMITED</span>
                                @endif
                                <span class="thumb-icon">🌊</span>
                            </div>

                            <div class="card-body">
                                <div class="category">{{ $product->category->name }}</div>
                                <div class="name">{{ $product->name }}</div>
                                <div class="price">${{ number_format($product->price, 2) }}</div>

                                @if ($product->stock > 0)
                                    <p class="meta">In stock: {{ $product->stock }}</p>
                                @else
                                    <p class="soldout">SOLD OUT</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-box">
                    <p>No products match your filters.</p>
                    <a href="{{ route('products.index') }}" class="reset-link">Reset all</a>
                </div>
            @endif
        </div>

    </div>

    <script>
        const form = document.getElementById('shop-form');
        const searchInput = document.getElementById('search-input');
        const clearBtn = document.getElementById('search-clear');
        const resultInfo = document.getElementById('result-info');
        const shopProducts = document.getElementById('shop-products');

        let timer;
        let controller;

        // Ask the server for the filtered page, then swap in only the changed parts
        function updateProducts() {
            const params = new URLSearchParams(new FormData(form));

            // Remove empty values so the address bar stays clean
            [...params.entries()].forEach(([key, value]) => {
                if (value === '') params.delete(key);
            });

            const query = params.toString();
            const url = form.action + (query ? '?' + query : '');

            // Cancel the previous request if the user is still typing
            if (controller) controller.abort();
            controller = new AbortController();

            fetch(url, { signal: controller.signal })
                .then(response => response.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    resultInfo.innerHTML = doc.getElementById('result-info').innerHTML;
                    shopProducts.innerHTML = doc.getElementById('shop-products').innerHTML;
                    history.replaceState(null, '', url);
                })
                .catch(error => {
                    if (error.name !== 'AbortError') console.error(error);
                });
        }

        // Wait a short moment after typing stops
        function updateLater() {
            clearTimeout(timer);
            timer = setTimeout(updateProducts, 300);
        }

        // Show the X button only when the search bar has text
        function toggleClearBtn() {
            clearBtn.hidden = searchInput.value === '';
        }

        // Typing in the search bar or the price boxes
        searchInput.addEventListener('input', () => {
            toggleClearBtn();
            updateLater();
        });
        form.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', updateLater);
        });

        // Category and sort update immediately
        form.querySelectorAll('input[type="radio"], select').forEach(input => {
            input.addEventListener('change', updateProducts);
        });

        // The X button: empty the search bar and show everything again
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            toggleClearBtn();
            searchInput.focus();
            updateProducts();
        });

        // Pressing Enter should not reload the page
        form.addEventListener('submit', event => {
            event.preventDefault();
            updateProducts();
        });
        searchInput.addEventListener('keydown', event => {
            if (event.key === 'Enter') {
                event.preventDefault();
                updateProducts();
            }
        });
    </script>
@endsection