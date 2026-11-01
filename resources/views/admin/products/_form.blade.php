@csrf

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
    @error('name')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="category_id">Category</label>
    <select id="category_id" name="category_id" required>
        <option value="">Choose a category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ (string) old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
    @error('description')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label for="price">Price ($)</label>
        <input type="number" id="price" name="price" step="0.01" min="0"
               value="{{ old('price', $product->price) }}" required>
        @error('price')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" min="0"
               value="{{ old('stock', $product->stock) }}" required>
        @error('stock')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="collection">Collection (optional)</label>
    <input type="text" id="collection" name="collection" value="{{ old('collection', $product->collection) }}">
    @error('collection')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-check">
    <label>
        <input type="checkbox" name="is_featured" value="1"
            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
        Featured product
    </label>
</div>

<div class="form-check">
    <label>
        <input type="checkbox" name="is_limited" value="1"
            {{ old('is_limited', $product->is_limited) ? 'checked' : '' }}>
        Limited drop
    </label>
</div>

<div class="form-group">
    <label for="drop_ends_at">Drop ends at (optional, for limited drops)</label>
    <input type="datetime-local" id="drop_ends_at" name="drop_ends_at"
           value="{{ old('drop_ends_at', $product->drop_ends_at?->format('Y-m-d\TH:i')) }}">
    @error('drop_ends_at')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

{{-- Photos --}}
<div class="form-group">
    <label>Photos (at least 3, each up to 2 MB)</label>

    @if ($product->exists && $product->images->count() > 0)
        <p class="photo-hint">Current photos. Tick "Remove" on the ones you want to delete.</p>

        <div class="photo-grid">
            @foreach ($product->images as $image)
                <label class="photo-item">
                    <img src="{{ asset($image->path) }}" alt="">
                    <span class="photo-remove">
                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Remove
                    </span>
                </label>
            @endforeach
        </div>
    @endif

    <input type="file" id="images" name="images[]" accept="image/png,image/jpeg,image/webp" multiple
           {{ $product->exists ? '' : 'required' }}>
    <p class="photo-hint">
        {{ $product->exists ? 'Add more photos here. ' : '' }}Hold Ctrl to choose several files at once. JPG, PNG or WEBP.
    </p>

    @if ($errors->has('images') || $errors->has('images.*'))
        <p class="form-error">{{ $errors->first('images') ?: $errors->first('images.*') }}</p>
    @endif
</div>