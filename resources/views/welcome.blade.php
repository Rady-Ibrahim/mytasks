@extends('layouts.guest')

@section('title', config('app.name', 'MyTasks'))
@section('body-class', 'guest-shell--landing')

@section('full')
    <section class="landing-hero" data-testid="home-hero">
        <div class="landing-hero-media" aria-hidden="true">
            <img
                src="{{ asset('images/hero-workspace.png') }}"
                alt=""
                class="landing-hero-image"
            >
            <div class="landing-hero-shade"></div>
        </div>

        <div class="landing-hero-content">
            <div class="container landing-hero-inner">
                <div class="landing-copy reveal">
                    <p class="landing-brand">{{ config('app.name', 'MyTasks') }}</p>
                    <h1 class="landing-headline">{{ __('Personal Daily Task Manager') }}</h1>
                    <p class="landing-lead">
                        {{ __('Organize tasks, categories, reminders, and your daily productivity in one clean workspace.') }}
                    </p>
                    <div class="landing-actions">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 fw-semibold">{{ __('Get started') }}</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">{{ __('Log in') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
