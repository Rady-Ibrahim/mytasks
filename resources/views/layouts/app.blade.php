<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MyTasks'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell d-flex" data-layout="app">
        <aside class="app-sidebar border-end bg-body d-none d-lg-flex flex-column p-3" data-testid="app-sidebar">
            <a href="{{ url('/') }}" class="app-brand text-decoration-none mb-4">
                <i class="bi bi-check2-square me-2 text-primary"></i>
                {{ config('app.name', 'MyTasks') }}
            </a>

            <nav class="nav nav-pills flex-column gap-1">
                <a class="nav-link disabled" href="#" aria-disabled="true">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a class="nav-link disabled" href="#" aria-disabled="true">
                    <i class="bi bi-list-task me-2"></i> Tasks
                </a>
                <a class="nav-link disabled" href="#" aria-disabled="true">
                    <i class="bi bi-tags me-2"></i> Categories
                </a>
                <a class="nav-link disabled" href="#" aria-disabled="true">
                    <i class="bi bi-calendar3 me-2"></i> Calendar
                </a>
            </nav>
        </aside>

        <div class="flex-grow-1 d-flex flex-column min-vh-100">
            <header class="border-bottom bg-body px-3 px-lg-4 py-3 d-flex align-items-center justify-content-between" data-testid="app-topnav">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" disabled>
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="fw-semibold">@yield('page-title', 'MyTasks')</span>
                </div>

                <div class="d-flex align-items-center gap-2 text-secondary small">
                    <i class="bi bi-moon-stars"></i>
                    <span>Theme & auth come in later phases</span>
                </div>
            </header>

            <main class="flex-grow-1 p-3 p-lg-4">
                @include('partials.flash')

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
