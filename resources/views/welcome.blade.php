@extends('layouts.guest')

@section('title', config('app.name', 'MyTasks'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" data-testid="home-hero">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-3 text-primary">
                        <i class="bi bi-check2-square fs-3"></i>
                        <span class="app-brand fs-4">{{ config('app.name', 'MyTasks') }}</span>
                    </div>

                    <h1 class="h2 mb-3">Personal Daily Task Manager</h1>
                    <p class="lead text-secondary mb-4">
                        Organize tasks, categories, reminders, and your daily productivity in one clean workspace.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('register') }}" class="btn btn-primary">Get started</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Log in</a>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary-subtle text-primary-emphasis border">Laravel 12</span>
                        <span class="badge bg-primary-subtle text-primary-emphasis border">Blade</span>
                        <span class="badge bg-primary-subtle text-primary-emphasis border">Bootstrap 5</span>
                        <span class="badge bg-primary-subtle text-primary-emphasis border">Vite</span>
                        <span class="badge bg-primary-subtle text-primary-emphasis border">MySQL</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
