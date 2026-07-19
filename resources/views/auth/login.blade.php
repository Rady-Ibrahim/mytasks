@extends('layouts.guest')

@section('title', 'Log in — '.config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <a href="{{ route('home') }}" class="text-decoration-none app-brand text-primary">
                            <i class="bi bi-check2-square me-1"></i>{{ config('app.name') }}
                        </a>
                        <h1 class="h4 mt-3 mb-1">Welcome back</h1>
                        <p class="text-secondary small mb-0">Sign in to manage your tasks</p>
                    </div>

                    @include('partials.flash')

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autofocus
                                autocomplete="username"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label mb-0">Password</label>
                                <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror mt-2"
                                required
                                autocomplete="current-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Log in</button>
                    </form>

                    <p class="text-center text-secondary small mt-4 mb-0">
                        Don't have an account?
                        <a href="{{ route('register') }}">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
