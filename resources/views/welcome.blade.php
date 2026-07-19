@extends('layouts.guest')

@section('title', config('app.name', 'MyTasks'))

@section('content')
    <section class="hero-landing" data-testid="home-hero">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <p class="brand-mark mb-2">{{ config('app.name', 'MyTasks') }}</p>
                <h1 class="h3 fw-semibold mb-3 text-white-50">{{ __('Personal Daily Task Manager') }}</h1>
                <p class="hero-copy mb-4">
                    {{ __('Organize tasks, categories, reminders, and your daily productivity in one clean workspace.') }}
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 fw-semibold">{{ __('Get started') }}</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">{{ __('Log in') }}</a>
                </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-center reveal reveal-delay-2">
                <div class="hero-orb" aria-hidden="true"></div>
            </div>
        </div>
    </section>
@endsection
