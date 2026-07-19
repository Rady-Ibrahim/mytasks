@extends('layouts.app')

@section('title', 'Dashboard — '.config('app.name'))
@section('page-title', 'Dashboard')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('Welcome, :name', ['name' => auth()->user()->name]) }}</h1>
            <p class="text-secondary mb-0">{{ __('Here\'s your productivity snapshot for today.') }}</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> {{ __('New task') }}
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">{{ __('Total Tasks') }}</div>
                    <div class="fs-3 fw-semibold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">{{ __('Completed') }}</div>
                    <div class="fs-3 fw-semibold text-success">{{ $stats['completed'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">{{ __('Pending') }}</div>
                    <div class="fs-3 fw-semibold">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">{{ __('Overdue') }}</div>
                    <div class="fs-3 fw-semibold text-danger">{{ $stats['overdue'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">{{ __('Completion') }}</span>
                        <strong>{{ $stats['completion_percentage'] }}%</strong>
                    </div>
                    <div class="progress" role="progressbar" aria-valuenow="{{ $stats['completion_percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-success" style="width: {{ $stats['completion_percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">{{ __('Weekly Progress') }}</span>
                        <strong>{{ $stats['weekly_progress'] }}%</strong>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar" style="width: {{ $stats['weekly_progress'] }}%"></div>
                    </div>
                    <div class="small text-secondary">{{ __(':completed / :total due this week', ['completed' => $stats['week_completed'], 'total' => $stats['week_total']]) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">{{ __('Monthly Progress') }}</span>
                        <strong>{{ $stats['monthly_progress'] }}%</strong>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-info" style="width: {{ $stats['monthly_progress'] }}%"></div>
                    </div>
                    <div class="small text-secondary">{{ __(':completed / :total due this month', ['completed' => $stats['month_completed'], 'total' => $stats['month_total']]) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => __("Today's Tasks"),
                'tasks' => $todayTasks,
                'empty' => __('No tasks due today.'),
                'icon' => 'bi-sun',
            ])
        </div>
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => __('Overdue'),
                'tasks' => $overdueTasks,
                'empty' => __('Nothing overdue. Nice work!'),
                'icon' => 'bi-exclamation-triangle',
            ])
        </div>
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => __('Upcoming'),
                'tasks' => $upcomingTasks,
                'empty' => __('No upcoming tasks scheduled.'),
                'icon' => 'bi-calendar-event',
            ])
        </div>
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => __('Recently Completed'),
                'tasks' => $completedTasks,
                'empty' => __('No completed tasks yet.'),
                'icon' => 'bi-check2-circle',
            ])
        </div>
        <div class="col-12">
            @include('dashboard._task-list', [
                'title' => __('Recent Activity'),
                'tasks' => $recentActivity,
                'empty' => __('No recent activity.'),
                'icon' => 'bi-activity',
            ])
        </div>
    </div>
@endsection
