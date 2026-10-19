@extends('layouts.app')

@section('title', 'My Account | WAVE SHOP')

@section('content')
    <div class="auth-card">
        <h1>My account</h1>
        <p class="auth-sub">Your WAVE SHOP details.</p>

        <div class="account-row">
            <span class="account-label">Name</span>
            <span>{{ Auth::user()->name }}</span>
        </div>

        <div class="account-row">
            <span class="account-label">Email</span>
            <span>{{ Auth::user()->email }}</span>
        </div>

        <div class="account-row">
            <span class="account-label">Member since</span>
            <span>{{ Auth::user()->created_at->format('d M Y') }}</span>
        </div>
    </div>
@endsection