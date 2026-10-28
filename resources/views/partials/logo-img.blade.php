@php
    $logoFile = 'images/Logo3(W).png';
    $hasLogo = file_exists(public_path($logoFile));
@endphp

@if ($hasLogo)
    <img src="{{ asset($logoFile) }}" alt="WAVE SHOP" class="{{ $class ?? 'logo-img' }}">
@else
    WAVE <span>SHOP</span>
@endif