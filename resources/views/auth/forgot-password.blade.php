@extends('layouts.guest')

@section('title', 'Forgot password — '.config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <a href="{{ route('home') }}" class="text-decoration-none app-brand text-primary">
                            <i class="bi bi-check2-square me-1"></i>{{ config('app.name') }}
                        </a>
                        <h1 class="h4 mt-3 mb-1">Forgot password</h1>
                        <p class="text-secondary small mb-0">We'll email you a reset link</p>
                    </div>

                    @include('partials.flash')

                    <form method="POST" action="{{ route('password.email') }}" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Send reset link</button>
                    </form>

                    <p class="text-center text-secondary small mt-4 mb-0">
                        <a href="{{ route('login') }}">Back to login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
