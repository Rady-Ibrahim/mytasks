@extends('layouts.guest')

@section('title', __('Register').' — '.config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 reveal">
            <div class="auth-panel p-4 p-md-5">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none app-brand text-primary">
                        <i class="bi bi-check2-square me-1"></i>{{ config('app.name') }}
                    </a>
                    <h1 class="h4 mt-3 mb-1 fw-bold">{{ __('Create your account') }}</h1>
                    <p class="text-secondary small mb-0">{{ __('Start organizing your daily tasks') }}</p>
                </div>

                @include('partials.flash')

                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control form-control-lg @error('name') is-invalid @enderror" required autofocus autocomplete="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required autocomplete="username">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="new-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm password') }}</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control form-control-lg" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">{{ __('Create account') }}</button>
                </form>

                <p class="text-center text-secondary small mt-4 mb-0">
                    {{ __('Already registered?') }}
                    <a href="{{ route('login') }}">{{ __('Log in') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection