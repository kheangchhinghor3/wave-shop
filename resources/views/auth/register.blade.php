@extends('layouts.app')

@section('title', 'Register | WAVE SHOP')

@section('content')
    <div class="auth-card">
        <h1>Create account</h1>
        <p class="auth-sub">Join WAVE SHOP and ride the wave.</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
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

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <div class="password-wrap">
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                    <button type="button" class="toggle-password" aria-label="Show or hide password">Show</button>
                </div>
            </div>

            <button type="submit" class="btn btn-full">Register</button>
        </form>

        <p class="auth-switch">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
@endsection