<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-bs-theme="{{ auth()->user()?->theme?->value ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MyTasks'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell d-flex" data-layout="app">
        <aside class="app-sidebar d-none d-lg-flex flex-column p-4" data-testid="app-sidebar">
            @include('partials.sidebar-nav')
        </aside>

        <div class="offcanvas offcanvas-start app-offcanvas" tabindex="-1" id="appSidebarOffcanvas" aria-labelledby="appSidebarOffcanvasLabel">
            <div class="offcanvas-header border-0">
                <h2 class="offcanvas-title h5 app-brand text-white" id="appSidebarOffcanvasLabel">
                    <i class="bi bi-check2-square me-1"></i>{{ config('app.name') }}
                </h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="{{ __('Cancel') }}"></button>
            </div>
            <div class="offcanvas-body p-3">
                @include('partials.sidebar-nav', ['compact' => true])
            </div>
        </div>

        <div class="flex-grow-1 d-flex flex-column min-vh-100">
            <header class="app-topnav px-3 px-lg-4 py-3 d-flex align-items-center justify-content-between" data-testid="app-topnav">
                <div class="d-flex align-items-center gap-2">
                    <button
                        class="topnav-icon d-lg-none"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#appSidebarOffcanvas"
                        aria-controls="appSidebarOffcanvas"
                        aria-label="{{ __('Menu') }}"
                    >
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="fw-bold">@yield('page-title', 'MyTasks')</span>
                </div>

                <div class="d-flex align-items-center gap-1 gap-md-2 topnav-actions">
                    @auth
                        <form method="GET" action="{{ route('tasks.index') }}" class="d-none d-md-flex me-1" role="search">
                            <div class="input-group input-group-sm search-pill" style="min-width: 220px;">
                                <span class="input-group-text border-0 bg-transparent pe-0"><i class="bi bi-search"></i></span>
                                <input
                                    type="search"
                                    name="q"
                                    value="{{ request('q') }}"
                                    class="form-control border-0 bg-transparent"
                                    placeholder="{{ __('Search tasks...') }}"
                                    aria-label="{{ __('Search tasks...') }}"
                                >
                            </div>
                        </form>

                        <a href="{{ route('notifications.index') }}" class="topnav-icon position-relative" title="{{ __('Notifications') }}">
                            <i class="bi bi-bell"></i>
                            @if (($unreadNotificationsCount ?? 0) > 0)
                                <span class="topnav-badge">
                                    {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </a>

                        @php
                            $currentTheme = auth()->user()->theme ?? \App\Enums\Theme::Light;
                            $nextTheme = $currentTheme->toggle();
                        @endphp

                        <form method="POST" action="{{ route('theme.update') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="theme" value="{{ $nextTheme->value }}">
                            <button
                                type="submit"
                                class="topnav-icon"
                                title="{{ __('Switch to :theme mode', ['theme' => $nextTheme->label()]) }}"
                                data-testid="theme-toggle"
                            >
                                <i class="bi {{ $currentTheme === \App\Enums\Theme::Dark ? 'bi-sun' : 'bi-moon-stars' }}"></i>
                            </button>
                        </form>

                        @include('partials.locale-switcher')

                        <a href="{{ route('profile.edit') }}" class="topnav-icon" title="{{ __('Profile') }}">
                            <i class="bi bi-person"></i>
                        </a>

                        <div class="topnav-divider d-none d-md-block"></div>

                        <span class="small text-secondary d-none d-lg-inline topnav-user">{{ auth()->user()->name }}</span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="topnav-icon topnav-icon-muted" title="{{ __('Log out') }}">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    @else
                        @include('partials.locale-switcher')
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">{{ __('Log in') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('Register') }}</a>
                    @endauth
                </div>
            </header>

            <main class="app-main flex-grow-1 p-3 p-lg-4">
                @include('partials.flash')

                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.loading')

    <script>
        window.appI18n = {
            areYouSure: @json(__('Are you sure?')),
            cannotUndo: @json(__('This action cannot be undone.')),
            yes: @json(__('Yes')),
            cancel: @json(__('Cancel')),
            delete: @json(__('Delete')),
            loading: @json(__('Loading...')),
        };
    </script>

    @stack('scripts')
</body>
</html>
