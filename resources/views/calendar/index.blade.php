@extends('layouts.app')

@section('title', 'Calendar — '.config('app.name'))
@section('page-title', 'Calendar')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Calendar</h1>
            <p class="text-secondary mb-0">
                @if ($view === 'day')
                    {{ $date->format('l, F j, Y') }}
                @elseif ($view === 'week')
                    {{ $start->format('M j') }} – {{ $end->format('M j, Y') }}
                @else
                    {{ $date->format('F Y') }}
                @endif
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <div class="btn-group">
                <a href="{{ route('calendar', ['view' => 'day', 'date' => $date->toDateString()]) }}" class="btn btn-sm {{ $view === 'day' ? 'btn-primary' : 'btn-outline-primary' }}">Day</a>
                <a href="{{ route('calendar', ['view' => 'week', 'date' => $date->toDateString()]) }}" class="btn btn-sm {{ $view === 'week' ? 'btn-primary' : 'btn-outline-primary' }}">Week</a>
                <a href="{{ route('calendar', ['view' => 'month', 'date' => $date->toDateString()]) }}" class="btn btn-sm {{ $view === 'month' ? 'btn-primary' : 'btn-outline-primary' }}">Month</a>
            </div>

            <div class="btn-group">
                <a href="{{ route('calendar', ['view' => $view, 'date' => $previous->toDateString()]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <a href="{{ route('calendar', ['view' => $view, 'date' => now()->toDateString()]) }}" class="btn btn-sm btn-outline-secondary">Today</a>
                <a href="{{ route('calendar', ['view' => $view, 'date' => $next->toDateString()]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <a href="{{ route('tasks.create', ['due_date' => $date->toDateString()]) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Add task
            </a>
        </div>
    </div>

    @if ($view === 'month')
        <div class="card border-0 shadow-sm">
            <div class="card-body p-2 p-md-3">
                <div class="row row-cols-7 g-2 text-center small text-secondary mb-2 d-none d-md-flex">
                    @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $weekday)
                        <div class="col fw-semibold">{{ $weekday }}</div>
                    @endforeach
                </div>
                <div class="row row-cols-2 row-cols-md-7 g-2">
                    @foreach ($days as $day)
                        @php
                            $key = $day->toDateString();
                            $dayTasks = $tasksByDate->get($key, collect());
                            $inMonth = $day->month === $date->month;
                        @endphp
                        <div class="col">
                            <div class="border rounded-3 p-2 h-100 {{ $day->isToday() ? 'border-primary' : '' }} {{ $inMonth ? 'bg-body' : 'bg-body-tertiary' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="{{ route('calendar', ['view' => 'day', 'date' => $key]) }}" class="fw-semibold text-decoration-none {{ $inMonth ? '' : 'text-secondary' }}">
                                        {{ $day->day }}
                                    </a>
                                    <a href="{{ route('tasks.create', ['due_date' => $key]) }}" class="small text-secondary" title="Add task">+</a>
                                </div>
                                <div class="d-grid gap-1">
                                    @foreach ($dayTasks->take(3) as $task)
                                        <a href="{{ route('tasks.show', $task) }}" class="badge bg-primary-subtle text-primary text-truncate text-decoration-none">
                                            {{ $task->title }}
                                        </a>
                                    @endforeach
                                    @if ($dayTasks->count() > 3)
                                        <span class="small text-secondary">+{{ $dayTasks->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($days as $day)
                @php
                    $key = $day->toDateString();
                    $dayTasks = $tasksByDate->get($key, collect());
                @endphp
                <div class="{{ $view === 'day' ? 'col-12' : 'col-md-6 col-xl-4' }}">
                    <div class="card border-0 shadow-sm h-100 {{ $day->isToday() ? 'border border-primary' : '' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h6 mb-0">{{ $day->format('D, M j') }}</h2>
                                <a href="{{ route('tasks.create', ['due_date' => $key]) }}" class="btn btn-sm btn-outline-primary">Add</a>
                            </div>

                            @if ($dayTasks->isEmpty())
                                <p class="text-secondary small mb-0">No tasks.</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach ($dayTasks as $task)
                                        <li class="list-group-item px-0">
                                            <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-decoration-none">{{ $task->title }}</a>
                                            <div class="small text-secondary">
                                                <span class="badge {{ $task->status->badgeClass() }}">{{ $task->status->label() }}</span>
                                                @if ($task->due_time)
                                                    · {{ \Illuminate\Support\Str::of($task->due_time)->substr(0, 5) }}
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
