@if ($product->is_limited && $product->drop_ends_at)
    <div class="drop-timer {{ $product->dropEnded() ? 'is-ended' : '' }}"
         data-end="{{ $product->drop_ends_at->toIso8601String() }}">
        ⏳ {{ $product->dropEnded() ? 'Drop ended' : 'Limited drop' }}
    </div>
@endif