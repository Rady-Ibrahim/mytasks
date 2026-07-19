@extends('layouts.app')

@section('title', 'Dashboard — '.config('app.name'))
@section('page-title', 'Dashboard')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-secondary mb-0">Here's your productivity snapshot for today.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New task
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Total Tasks</div>
                    <div class="fs-3 fw-semibold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Completed</div>
                    <div class="fs-3 fw-semibold text-success">{{ $stats['completed'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Pending</div>
                    <div class="fs-3 fw-semibold">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Overdue</div>
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
                        <span class="text-secondary small">Completion</span>
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
                        <span class="text-secondary small">Weekly Progress</span>
                        <strong>{{ $stats['weekly_progress'] }}%</strong>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar" style="width: {{ $stats['weekly_progress'] }}%"></div>
                    </div>
                    <div class="small text-secondary">{{ $stats['week_completed'] }} / {{ $stats['week_total'] }} due this week</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Monthly Progress</span>
                        <strong>{{ $stats['monthly_progress'] }}%</strong>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-info" style="width: {{ $stats['monthly_progress'] }}%"></div>
                    </div>
                    <div class="small text-secondary">{{ $stats['month_completed'] }} / {{ $stats['month_total'] }} due this month</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => "Today's Tasks",
                'tasks' => $todayTasks,
                'empty' => 'No tasks due today.',
                'icon' => 'bi-sun',
            ])
        </div>
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => 'Overdue',
                'tasks' => $overdueTasks,
                'empty' => 'Nothing overdue. Nice work!',
                'icon' => 'bi-exclamation-triangle',
            ])
        </div>
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => 'Upcoming',
                'tasks' => $upcomingTasks,
                'empty' => 'No upcoming tasks scheduled.',
                'icon' => 'bi-calendar-event',
            ])
        </div>
        <div class="col-lg-6">
            @include('dashboard._task-list', [
                'title' => 'Recently Completed',
                'tasks' => $completedTasks,
                'empty' => 'No completed tasks yet.',
                'icon' => 'bi-check2-circle',
            ])
        </div>
        <div class="col-12">
            @include('dashboard._task-list', [
                'title' => 'Recent Activity',
                'tasks' => $recentActivity,
                'empty' => 'No recent activity.',
                'icon' => 'bi-activity',
            ])
        </div>
    </div>
@endsection
