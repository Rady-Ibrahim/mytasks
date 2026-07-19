@extends('layouts.app')

@section('title', 'Tasks — '.config('app.name'))
@section('page-title', 'Tasks')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Tasks</h1>
            <p class="text-secondary mb-0">Search, filter, and sort your tasks.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tasks.trash') }}" class="btn btn-outline-secondary">
                <i class="bi bi-trash me-1"></i> Trash
            </a>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> New task
            </a>
        </div>
    </div>

    @include('tasks._filters')

    @if ($tasks->isEmpty())
        <x-empty-state
            title="No tasks found"
            message="Try adjusting your search or filters, or create a new task."
            icon="bi-list-task"
        >
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Clear filters</a>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create task</a>
            </div>
        </x-empty-state>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due</th>
                            <th class="text-end">Actions</th>
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
                                            {{ $task->category->name }}
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
                                        {{ $task->due_date->format('M j, Y') }}
                                        @if ($task->due_time)
                                            <span class="text-secondary small">{{ \Illuminate\Support\Str::of($task->due_time)->substr(0, 5) }}</span>
                                        @endif
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    @if ($task->status !== \App\Enums\TaskStatus::Completed)
                                        <form method="POST" action="{{ route('tasks.complete', $task) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Complete">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('tasks.reopen', $task) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Reopen">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('tasks.duplicate', $task) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Duplicate">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form
                                        method="POST"
                                        action="{{ route('tasks.destroy', $task) }}"
                                        class="d-inline"
                                        data-confirm-delete
                                        data-confirm-title="Delete task?"
                                        data-confirm-text="This will move the task to trash."
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
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
