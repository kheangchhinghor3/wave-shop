@csrf

@php
    // Keep the box ticked on a new coupon, and remember the choice after an error
    $isActive = old() ? (bool) old('is_active') : ($coupon->exists ? $coupon->is_active : true);
@endphp

<div class="form-group">
    <label for="code">Code</label>
    <input type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}"
           placeholder="For example SUMMER15" autocomplete="off" required style="text-transform: uppercase;">
    @error('code')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label for="type">Type</label>
        <select id="type" name="type" required>
            <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percent (%)</option>
            <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed amount ($)</option>
        </select>
        @error('type')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="value">Value</label>
        <input type="number" id="value" name="value" step="0.01" min="0.01"
               value="{{ old('value', $coupon->exists ? $coupon->value : '') }}" required>
        @error('value')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="min_order">Minimum order ($, optional)</label>
        <input type="number" id="min_order" name="min_order" step="0.01" min="0"
               value="{{ old('min_order', $coupon->exists && $coupon->min_order > 0 ? $coupon->min_order : '') }}">
        @error('min_order')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label for="usage_limit">Usage limit (optional)</label>
        <input type="number" id="usage_limit" name="usage_limit" min="1"
               value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Empty = unlimited">
        @error('usage_limit')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="expires_at">Expires at (optional)</label>
    <input type="datetime-local" id="expires_at" name="expires_at"
           value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}">
    @error('expires_at')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-check">
    <label>
        <input type="checkbox" name="is_active" value="1" {{ $isActive ? 'checked' : '' }}>
        Active (customers can use this coupon)
    </label>
</div>