@extends('layouts.guest')

@section('title', __('Log in').' — '.config('app.name'))
@section('body-class', 'guest-shell--auth')

@section('full')
    <div class="auth-split">
        <aside class="auth-split-visual d-none d-lg-flex" aria-hidden="true">
            <img
                src="{{ asset('images/hero-workspace.png') }}"
                alt=""
                class="auth-split-image"
            >
            <div class="auth-split-shade"></div>
            <div class="auth-split-caption reveal">
                <p class="auth-split-brand">{{ config('app.name') }}</p>
                <p class="auth-split-text">{{ __('Organize tasks, categories, reminders, and your daily productivity in one clean workspace.') }}</p>
            </div>
        </aside>

        <main class="auth-split-form">
            <div class="auth-split-form-inner reveal">
                <a href="{{ route('home') }}" class="auth-brand-link text-decoration-none">
                    <i class="bi bi-check2-square"></i>
                    <span>{{ config('app.name') }}</span>
                </a>

                <h1 class="auth-title">{{ __('Welcome back') }}</h1>
                <p class="auth-subtitle">{{ __('Sign in to manage your tasks') }}</p>

                @include('partials.flash')

                <form method="POST" action="{{ route('login') }}" novalidate class="auth-form">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required autofocus autocomplete="username">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                            <a href="{{ route('password.request') }}" class="auth-link small">{{ __('Forgot password?') }}</a>
                        </div>
                        <input id="password" type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror mt-2" required autocomplete="current-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                        <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">{{ __('Log in') }}</button>
                </form>

                <p class="auth-footer">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="auth-link">{{ __('Register') }}</a>
                </p>
            </div>
        </main>
    </div>
@endsection
