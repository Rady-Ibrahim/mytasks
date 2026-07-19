@extends('layouts.app')

@section('title', __('Tasks').' — '.config('app.name'))
@section('page-title', __('Tasks'))

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3 reveal">
        <div>
            <h1 class="h2 mb-1">{{ __('Tasks') }}</h1>
            <p class="text-secondary mb-0">{{ __('Search, filter, and sort your tasks.') }}</p>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('tasks.trash') }}" class="btn btn-soft">
                <i class="bi bi-trash"></i> {{ __('Trash') }}
            </a>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> {{ __('New task') }}
            </a>
        </div>
    </div>

    @include('tasks._filters')

    @if ($tasks->isEmpty())
        <x-empty-state
            title="{{ __('No tasks found') }}"
            message="{{ __('Try adjusting your search or filters, or create a new task.') }}"
            icon="bi-list-task"
        >
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="{{ route('tasks.index') }}" class="btn btn-soft">{{ __('Clear filters') }}</a>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">{{ __('Create task') }}</a>
            </div>
        </x-empty-state>
    @else
        <div class="panel overflow-hidden reveal reveal-delay-1">
            <div class="table-responsive">
                <table class="table table-modern table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Task') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Priority') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Due') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>
                                    <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-decoration-none">
                                        {{ $task->title }}
                                    </a>
                                    @if ($task->description)
                                        <div class="small text-secondary text-truncate" style="max-width: 280px;">
                                            {{ $task->description }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($task->category)
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <i class="bi {{ $task->category->icon }}" style="color: {{ $task->category->color }}"></i>
                                            {{ __($task->category->name) }}
                                        </span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $task->priority->badgeClass() }}">{{ $task->priority->label() }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $task->status->badgeClass() }}">{{ $task->status->label() }}</span>
                                </td>
                                <td>
                                    @if ($task->due_date)
                                        {{ $task->due_date->translatedFormat('d M Y') }}
                                        @if ($task->due_time)
                                            <span class="text-secondary small">{{ \Illuminate\Support\Str::of($task->due_time)->substr(0, 5) }}</span>
                                        @endif
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="action-group">
                                        @if ($task->status !== \App\Enums\TaskStatus::Completed)
                                            <form method="POST" action="{{ route('tasks.complete', $task) }}">
                                                @csrf
                                                <button type="submit" class="btn-icon btn-icon-success" title="{{ __('Complete') }}">
                                                    <i class="bi bi-check2"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('tasks.reopen', $task) }}">
                                                @csrf
                                                <button type="submit" class="btn-icon" title="{{ __('Reopen') }}">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('tasks.duplicate', $task) }}">
                                            @csrf
                                            <button type="submit" class="btn-icon" title="{{ __('Duplicate') }}">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('tasks.edit', $task) }}" class="btn-icon" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('tasks.destroy', $task) }}"
                                            data-confirm-delete
                                            data-confirm-title="{{ __('Delete task?') }}"
                                            data-confirm-text="{{ __('This will move the task to trash.') }}"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" title="{{ __('Delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $tasks->links() }}
        </div>
    @endif
@endsection
