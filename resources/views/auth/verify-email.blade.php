@extends('layouts.guest')

@section('title', __('Verify your email').' — '.config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 reveal">
            <div class="auth-panel p-4 p-md-5 text-center">
                <div class="mb-3 text-primary">
                    <i class="bi bi-envelope-check fs-1"></i>
                </div>
                <h1 class="h4 mb-3 fw-bold">{{ __('Verify your email') }}</h1>
                <p class="text-secondary mb-4">
                    {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed you.') }}
                </p>
                <p class="text-secondary small mb-4">
                    {{ __("If you didn't receive the email, we can send another one.") }}
                </p>

                @include('partials.flash')

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ __('Resend verification email') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        {{ __('Log out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
