<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - WAVE SHOP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 30px; color: #111; }
        a { color: inherit; text-decoration: none; }
        h1 { margin-top: 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .card:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.15); }
        .category { color: #888; font-size: 13px; text-transform: uppercase; }
        .name { font-size: 18px; font-weight: bold; margin: 8px 0; }
        .price { font-size: 20px; color: #e0508a; font-weight: bold; }
        .badge { display: inline-block; background: #111; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-bottom: 6px; }
        .soldout { color: #c00; font-weight: bold; }
    </style>
</head>
<body>
    <h1>WAVE SHOP Products</h1>
    <p>{{ $products->count() }} products found</p>

    <div class="grid">
        @foreach ($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="card">
                @if ($product->is_limited)
                    <span class="badge">LIMITED</span>
                @endif

                <div class="category">{{ $product->category->name }}</div>
                <div class="name">{{ $product->name }}</div>
                <div class="price">${{ number_format($product->price, 2) }}</div>

                @if ($product->stock > 0)
                    <p>In stock: {{ $product->stock }}</p>
                @else
                    <p class="soldout">SOLD OUT</p>
                @endif
            </a>
        @endforeach
    </div>
</body>
</html>