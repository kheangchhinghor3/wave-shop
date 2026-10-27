@php
    // LOGO TEST: ?logo=1, ?logo=2, ?logo=3 (and ?logo=0 for the text logo)
    if (request()->has('logo')) {
        session(['logo_test' => request('logo')]);
    }

    // Number => file inside public/images
    $logoFiles = [
        '1' => 'images/Logo1(W).png',
        '2' => 'images/Logo2(W).png',
        '3' => 'images/Logo3(W).png',
    ];

    $logoNumber = session('logo_test');
    $logoFile = null;

    if (isset($logoFiles[$logoNumber]) && file_exists(public_path($logoFiles[$logoNumber]))) {
        $logoFile = $logoFiles[$logoNumber];
    }
@endphp

@if ($logoFile)
    <img src="{{ asset($logoFile) }}" alt="WAVE SHOP" class="{{ $class ?? 'logo-img' }}">
@else
    WAVE <span>SHOP</span>
@endif