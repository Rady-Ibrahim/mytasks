<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ auth()->user()?->theme?->value ?? 'light' }}">
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
            @include('partials.sidebar-nav')
        </aside>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="appSidebarOffcanvas" aria-labelledby="appSidebarOffcanvasLabel">
            <div class="offcanvas-header">
                <h2 class="offcanvas-title h5 app-brand" id="appSidebarOffcanvasLabel">
                    <i class="bi bi-check2-square me-1 text-primary"></i>{{ config('app.name') }}
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-3">
                @include('partials.sidebar-nav', ['compact' => true])
            </div>
        </div>

        <div class="flex-grow-1 d-flex flex-column min-vh-100">
            <header class="border-bottom bg-body px-3 px-lg-4 py-3 d-flex align-items-center justify-content-between" data-testid="app-topnav">
                <div class="d-flex align-items-center gap-2">
                    <button
                        class="btn btn-outline-secondary btn-sm d-lg-none"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#appSidebarOffcanvas"
                        aria-controls="appSidebarOffcanvas"
                    >
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="fw-semibold">@yield('page-title', 'MyTasks')</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <form method="GET" action="{{ route('tasks.index') }}" class="d-none d-md-flex" role="search">
                            <div class="input-group input-group-sm" style="min-width: 220px;">
                                <span class="input-group-text bg-body"><i class="bi bi-search"></i></span>
                                <input
                                    type="search"
                                    name="q"
                                    value="{{ request('q') }}"
                                    class="form-control"
                                    placeholder="Search tasks..."
                                    aria-label="Search tasks"
                                >
                            </div>
                        </form>

                        @php
                            $currentTheme = auth()->user()->theme ?? \App\Enums\Theme::Light;
                            $nextTheme = $currentTheme->toggle();
                        @endphp

                        <form method="POST" action="{{ route('theme.update') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="theme" value="{{ $nextTheme->value }}">
                            <button
                                type="submit"
                                class="btn btn-outline-secondary btn-sm"
                                title="Switch to {{ $nextTheme->label() }} mode"
                                data-testid="theme-toggle"
                            >
                                <i class="bi {{ $currentTheme === \App\Enums\Theme::Dark ? 'bi-sun' : 'bi-moon-stars' }}"></i>
                            </button>
                        </form>

                        <span class="small text-secondary d-none d-md-inline">{{ auth()->user()->name }}</span>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm" title="Profile">
                            <i class="bi bi-person"></i>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-arrow-right"></i>
                                <span class="d-none d-sm-inline">Log out</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Log in</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                    @endauth
                </div>
            </header>

            <main class="flex-grow-1 p-3 p-lg-4">
                @include('partials.flash')

                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.loading')

    @stack('scripts')
</body>
</html>
