@extends('layouts.app')

@section('title', 'Tasks — '.config('app.name'))
@section('page-title', 'Tasks')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Tasks</h1>
            <p class="text-secondary mb-0">Create and manage your daily tasks.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New task
        </a>
    </div>

    @if ($tasks->isEmpty())
        <x-empty-state
            title="No tasks yet"
            message="Create your first task to start tracking your day."
            icon="bi-list-task"
        >
            <a href="{{ route('tasks.create') }}" class="btn btn-primary mt-3">Create task</a>
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
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form
                                        method="POST"
                                        action="{{ route('tasks.destroy', $task) }}"
                                        class="d-inline"
                                        data-confirm-delete
                                        data-confirm-title="Delete task?"
                                        data-confirm-text="This will soft-delete the task."
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
