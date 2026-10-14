<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - WAVE SHOP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 30px; color: #111; }
        a { color: #e0508a; text-decoration: none; }
        .detail { background: #fff; border-radius: 16px; padding: 30px; max-width: 700px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .badge { display: inline-block; background: #111; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-bottom: 8px; }
        .category { color: #888; font-size: 13px; text-transform: uppercase; }
        h1 { margin: 8px 0; }
        .price { font-size: 26px; color: #e0508a; font-weight: bold; }
        .soldout { color: #c00; font-weight: bold; }
        .meta { color: #555; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-top: 16px; }
        .card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <p><a href="{{ route('products.index') }}">&larr; Back to all products</a></p>

    <div class="detail">
        @if ($product->is_limited)
            <span class="badge">LIMITED DROP</span>
        @endif

        <div class="category">{{ $product->category->name }}</div>
        <h1>{{ $product->name }}</h1>
        <div class="price">${{ number_format($product->price, 2) }}</div>

        <p>{{ $product->description }}</p>

        @if ($product->collection)
            <p class="meta">Collection: {{ $product->collection }}</p>
        @endif

        @if ($product->stock > 0)
            <p class="meta">
                @if ($product->is_limited)
                    Only {{ $product->stock }} left
                @else
                    In stock: {{ $product->stock }}
                @endif
            </p>
        @else
            <p class="soldout">SOLD OUT</p>
        @endif
    </div>

    @if ($relatedProducts->count() > 0)
        <h2>Related products</h2>
        <div class="grid">
            @foreach ($relatedProducts as $related)
                <div class="card">
                    <a href="{{ route('products.show', $related->slug) }}"><strong>{{ $related->name }}</strong></a>
                    <div class="price">${{ number_format($related->price, 2) }}</div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>