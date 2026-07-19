@extends('layouts.app')

@section('title', 'Trash — '.config('app.name'))
@section('page-title', 'Trash')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Trash</h1>
            <p class="text-secondary mb-0">Restore deleted tasks or remove them permanently.</p>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to tasks
        </a>
    </div>

    @if ($tasks->isEmpty())
        <x-empty-state
            title="Trash is empty"
            message="Deleted tasks will appear here."
            icon="bi-trash"
        />
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Deleted</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="fw-semibold">{{ $task->title }}</td>
                                <td>
                                    @if ($task->category)
                                        {{ $task->category->name }}
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td>{{ $task->deleted_at?->diffForHumans() }}</td>
                                <td class="text-end text-nowrap">
                                    <form method="POST" action="{{ route('tasks.restore', $task) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                                    </form>
                                    <form
                                        method="POST"
                                        action="{{ route('tasks.force-delete', $task) }}"
                                        class="d-inline"
                                        data-confirm-delete
                                        data-confirm-title="Permanently delete?"
                                        data-confirm-text="This cannot be undone."
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete forever</button>
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
