@php($compact = $compact ?? false)

@unless ($compact)
    <a href="{{ route('dashboard') }}" class="app-brand text-decoration-none mb-4">
        <i class="bi bi-check2-square me-2 text-primary"></i>
        {{ config('app.name', 'MyTasks') }}
    </a>
@endunless

<nav class="nav nav-pills flex-column gap-1" data-testid="sidebar-nav">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
        <i class="bi bi-list-task me-2"></i> Tasks
    </a>
    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
        <i class="bi bi-tags me-2"></i> Categories
    </a>
    <a class="nav-link disabled" href="#" aria-disabled="true">
        <i class="bi bi-calendar3 me-2"></i> Calendar
    </a>
    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
        <i class="bi bi-person-circle me-2"></i> Profile
    </a>
</nav>
