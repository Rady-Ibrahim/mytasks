@extends('layouts.app')

@section('title', 'Dashboard — '.config('app.name'))
@section('page-title', __('Dashboard'))

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3 reveal">
        <div>
            <h1 class="h2 mb-1">{{ __('Welcome, :name', ['name' => auth()->user()->name]) }}</h1>
            <p class="text-secondary mb-0">{{ __('Here\'s your productivity snapshot for today.') }}</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg me-1"></i> {{ __('New task') }}
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3 reveal reveal-delay-1">
            <div class="panel stat-tile h-100" style="--tile-accent: #0f766e;">
                <i class="bi bi-collection-fill stat-icon"></i>
                <div class="stat-label">{{ __('Total Tasks') }}</div>
                <div class="stat-value" data-count-to="{{ $stats['total'] }}">0</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-delay-2">
            <div class="panel stat-tile h-100" style="--tile-accent: #059669;">
                <i class="bi bi-check2-circle stat-icon"></i>
                <div class="stat-label">{{ __('Completed') }}</div>
                <div class="stat-value text-success" data-count-to="{{ $stats['completed'] }}">0</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-delay-3">
            <div class="panel stat-tile h-100" style="--tile-accent: #d4a373;">
                <i class="bi bi-hourglass-split stat-icon"></i>
                <div class="stat-label">{{ __('Pending') }}</div>
                <div class="stat-value" data-count-to="{{ $stats['pending'] }}">0</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-delay-4">
            <div class="panel stat-tile h-100" style="--tile-accent: #e07a5f;">
                <i class="bi bi-exclamation-octagon-fill stat-icon"></i>
                <div class="stat-label">{{ __('Overdue') }}</div>
                <div class="stat-value text-danger" data-count-to="{{ $stats['overdue'] }}">0</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 reveal reveal-delay-1">
            <div class="panel h-100 p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="stat-label mb-0">{{ __('Completion') }}</span>
                    <strong class="fs-5" data-count-to="{{ $stats['completion_percentage'] }}">0</strong><span class="fs-5 fw-bold">%</span>
                </div>
                <div class="progress-modern" role="progressbar" aria-valuenow="{{ $stats['completion_percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-success" data-progress-width="{{ $stats['completion_percentage'] }}" style="width: 0%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 reveal reveal-delay-2">
            <div class="panel h-100 p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="stat-label mb-0">{{ __('Weekly Progress') }}</span>
                    <strong class="fs-5" data-count-to="{{ $stats['weekly_progress'] }}">0</strong><span class="fs-5 fw-bold">%</span>
                </div>
                <div class="progress-modern mb-2">
                    <div class="progress-bar" data-progress-width="{{ $stats['weekly_progress'] }}" style="width: 0%"></div>
                </div>
                <div class="small text-secondary">{{ __(':completed / :total due this week', ['completed' => $stats['week_completed'], 'total' => $stats['week_total']]) }}</div>
            </div>
        </div>
        <div class="col-md-4 reveal reveal-delay-3">
            <div class="panel h-100 p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="stat-label mb-0">{{ __('Monthly Progress') }}</span>
                    <strong class="fs-5" data-count-to="{{ $stats['monthly_progress'] }}">0</strong><span class="fs-5 fw-bold">%</span>
                </div>
                <div class="progress-modern mb-2">
                    <div class="progress-bar" style="background: linear-gradient(90deg, #0f766e, #14b8a6); width: 0%" data-progress-width="{{ $stats['monthly_progress'] }}"></div>
                </div>
                <div class="small text-secondary">{{ __(':completed / :total due this month', ['completed' => $stats['month_completed'], 'total' => $stats['month_total']]) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6 reveal reveal-delay-1">
            @include('dashboard._task-list', [
                'title' => __("Today's Tasks"),
                'tasks' => $todayTasks,
                'empty' => __('No tasks due today.'),
                'icon' => 'bi-sun',
            ])
        </div>
        <div class="col-lg-6 reveal reveal-delay-2">
            @include('dashboard._task-list', [
                'title' => __('Overdue'),
                'tasks' => $overdueTasks,
                'empty' => __('Nothing overdue. Nice work!'),
                'icon' => 'bi-exclamation-triangle',
            ])
        </div>
        <div class="col-lg-6 reveal reveal-delay-3">
            @include('dashboard._task-list', [
                'title' => __('Upcoming'),
                'tasks' => $upcomingTasks,
                'empty' => __('No upcoming tasks scheduled.'),
                'icon' => 'bi-calendar-event',
            ])
        </div>
        <div class="col-lg-6 reveal reveal-delay-4">
            @include('dashboard._task-list', [
                'title' => __('Recently Completed'),
                'tasks' => $completedTasks,
                'empty' => __('No completed tasks yet.'),
                'icon' => 'bi-check2-circle',
            ])
        </div>
        <div class="col-12 reveal reveal-delay-5">
            @include('dashboard._task-list', [
                'title' => __('Recent Activity'),
                'tasks' => $recentActivity,
                'empty' => __('No recent activity.'),
                'icon' => 'bi-activity',
            ])
        </div>
    </div>
@endsection
