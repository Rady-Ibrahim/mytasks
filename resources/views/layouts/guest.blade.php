<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MyTasks'))</title>

    @if (app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body-tertiary">
    <main class="min-vh-100 d-flex align-items-center py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

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
