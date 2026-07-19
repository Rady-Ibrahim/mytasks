@extends('layouts.app')

@section('title', __('Calendar').' — '.config('app.name'))
@section('page-title', __('Calendar'))

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3 reveal">
        <div>
            <h1 class="h2 mb-1">{{ __('Calendar') }}</h1>
            <p class="text-secondary mb-0 calendar-period">
                @if ($view === 'day')
                    {{ $date->format('l, F j, Y') }}
                @elseif ($view === 'week')
                    {{ $start->format('M j') }} – {{ $end->format('M j, Y') }}
                @else
                    {{ $date->format('F Y') }}
                @endif
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="calendar-view-switch" role="group" aria-label="{{ __('Calendar') }}">
                <a href="{{ route('calendar', ['view' => 'day', 'date' => $date->toDateString()]) }}" class="{{ $view === 'day' ? 'is-active' : '' }}">{{ __('Day') }}</a>
                <a href="{{ route('calendar', ['view' => 'week', 'date' => $date->toDateString()]) }}" class="{{ $view === 'week' ? 'is-active' : '' }}">{{ __('Week') }}</a>
                <a href="{{ route('calendar', ['view' => 'month', 'date' => $date->toDateString()]) }}" class="{{ $view === 'month' ? 'is-active' : '' }}">{{ __('Month') }}</a>
            </div>

            <div class="calendar-nav">
                <a href="{{ route('calendar', ['view' => $view, 'date' => $previous->toDateString()]) }}" class="calendar-nav-btn" title="{{ __('Previous') }}">
                    <i class="bi bi-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
                </a>
                <a href="{{ route('calendar', ['view' => $view, 'date' => now()->toDateString()]) }}" class="calendar-nav-today">{{ __('Today') }}</a>
                <a href="{{ route('calendar', ['view' => $view, 'date' => $next->toDateString()]) }}" class="calendar-nav-btn" title="{{ __('Next') }}">
                    <i class="bi bi-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
                </a>
            </div>

            <a href="{{ route('tasks.create', ['due_date' => $date->toDateString()]) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> {{ __('Add task') }}
            </a>
        </div>
    </div>

    @if ($view === 'month')
        <div class="panel calendar-month overflow-hidden reveal reveal-delay-1">
            <div class="calendar-weekdays d-none d-md-grid">
                @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $weekday)
                    <div>{{ __($weekday) }}</div>
                @endforeach
            </div>
            <div class="calendar-grid">
                @foreach ($days as $day)
                    @php
                        $key = $day->toDateString();
                        $dayTasks = $tasksByDate->get($key, collect());
                        $inMonth = $day->month === $date->month;
                        $isToday = $day->isToday();
                    @endphp
                    <div class="calendar-cell {{ $inMonth ? '' : 'is-outside' }} {{ $isToday ? 'is-today' : '' }} {{ $dayTasks->isNotEmpty() ? 'has-tasks' : '' }}">
                        <div class="calendar-cell-head">
                            <a href="{{ route('calendar', ['view' => 'day', 'date' => $key]) }}" class="calendar-day-num">
                                {{ $day->day }}
                            </a>
                            <a href="{{ route('tasks.create', ['due_date' => $key]) }}" class="calendar-cell-add" title="{{ __('Add task') }}">
                                <i class="bi bi-plus"></i>
                            </a>
                        </div>
                        <div class="calendar-cell-tasks">
                            @foreach ($dayTasks->take(3) as $task)
                                <a
                                    href="{{ route('tasks.show', $task) }}"
                                    class="calendar-chip priority-{{ $task->priority->value }}"
                                    title="{{ $task->title }}"
                                >
                                    @if ($task->category)
                                        <span class="calendar-chip-dot" style="background: {{ $task->category->color }}"></span>
                                    @endif
                                    <span class="calendar-chip-text">{{ $task->title }}</span>
                                </a>
                            @endforeach
                            @if ($dayTasks->count() > 3)
                                <a href="{{ route('calendar', ['view' => 'day', 'date' => $key]) }}" class="calendar-more">
                                    +{{ $dayTasks->count() - 3 }} {{ __('more') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($days as $day)
                @php
                    $key = $day->toDateString();
                    $dayTasks = $tasksByDate->get($key, collect());
                @endphp
                <div class="{{ $view === 'day' ? 'col-12' : 'col-md-6 col-xl' }} reveal reveal-delay-{{ min($loop->iteration, 5) }}">
                    <div class="panel calendar-day-card h-100 {{ $day->isToday() ? 'is-today' : '' }}">
                        <div class="calendar-day-card-head">
                            <div>
                                <div class="calendar-day-label">{{ $day->format('D') }}</div>
                                <h2 class="h5 mb-0 fw-bold">{{ $day->format('M j') }}</h2>
                            </div>
                            <a href="{{ route('tasks.create', ['due_date' => $key]) }}" class="btn btn-sm btn-outline-primary">{{ __('Add') }}</a>
                        </div>

                        @if ($dayTasks->isEmpty())
                            <div class="calendar-day-empty">
                                <i class="bi bi-calendar2-check"></i>
                                <span>{{ __('No tasks.') }}</span>
                            </div>
                        @else
                            <ul class="calendar-day-list">
                                @foreach ($dayTasks as $task)
                                    <li>
                                        <a href="{{ route('tasks.show', $task) }}" class="calendar-day-item">
                                            <span class="calendar-day-item-accent priority-{{ $task->priority->value }}"></span>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-semibold text-truncate">{{ $task->title }}</div>
                                                <div class="small text-secondary d-flex flex-wrap gap-2 align-items-center mt-1">
                                                    <span class="badge {{ $task->status->badgeClass() }}">{{ $task->status->label() }}</span>
                                                    @if ($task->due_time)
                                                        <span><i class="bi bi-clock me-1"></i>{{ \Illuminate\Support\Str::of($task->due_time)->substr(0, 5) }}</span>
                                                    @endif
                                                    @if ($task->category)
                                                        <span class="d-inline-flex align-items-center gap-1">
                                                            <i class="bi {{ $task->category->icon }}" style="color: {{ $task->category->color }}"></i>
                                                            {{ $task->category->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
