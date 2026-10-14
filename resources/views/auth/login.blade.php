@extends('layouts.app')

@section('title', 'Login | WAVE SHOP')

@section('content')
    <div class="auth-card">
        <h1>Welcome back</h1>
        <p class="auth-sub">Log in to continue shopping.</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" required>
                    <button type="button" class="toggle-password" aria-label="Show or hide password">Show</button>
                </div>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <label class="remember-row">
                <input type="checkbox" name="remember"> Remember me
            </label>

            <button type="submit" class="btn btn-full">Login</button>
        </form>

        <p class="auth-switch">
            New here? <a href="{{ route('register') }}">Create an account</a>
        </p>
    </div>
@endsection