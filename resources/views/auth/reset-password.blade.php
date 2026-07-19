@extends('layouts.guest')

@section('title', 'Reset password — '.config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <a href="{{ route('home') }}" class="text-decoration-none app-brand text-primary">
                            <i class="bi bi-check2-square me-1"></i>{{ config('app.name') }}
                        </a>
                        <h1 class="h4 mt-3 mb-1">Reset password</h1>
                        <p class="text-secondary small mb-0">Choose a new password for your account</p>
                    </div>

                    @include('partials.flash')

                    <form method="POST" action="{{ route('password.store') }}" novalidate>
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm password</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                                autocomplete="new-password"
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
