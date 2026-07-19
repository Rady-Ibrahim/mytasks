@php($compact = $compact ?? false)

@unless ($compact)
    <a href="{{ route('dashboard') }}" class="app-brand text-decoration-none mb-4 d-block">
        <i class="bi bi-check2-square me-2"></i>
        {{ config('app.name', 'MyTasks') }}
    </a>
@endunless

<nav class="nav nav-pills flex-column gap-1" data-testid="sidebar-nav">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i> {{ __('Dashboard') }}
    </a>
    <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
        <i class="bi bi-list-task me-2"></i> {{ __('Tasks') }}
    </a>
    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
        <i class="bi bi-tags me-2"></i> {{ __('Categories') }}
    </a>
    <a class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}" href="{{ route('calendar') }}">
        <i class="bi bi-calendar3 me-2"></i> {{ __('Calendar') }}
    </a>
    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
        <i class="bi bi-bell me-2"></i> {{ __('Notifications') }}
    </a>
    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
        <i class="bi bi-person-circle me-2"></i> {{ __('Profile') }}
    </a>
</nav>
