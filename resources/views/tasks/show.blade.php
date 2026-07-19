@extends('layouts.app')

@section('title', $task->title.' — '.config('app.name'))
@section('page-title', 'Task Details')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 mb-2">{{ $task->title }}</h1>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge {{ $task->priority->badgeClass() }}">{{ $task->priority->label() }}</span>
                <span class="badge {{ $task->status->badgeClass() }}">{{ $task->status->label() }}</span>
                @if ($task->category)
                    <span class="badge border" style="color: {{ $task->category->color }};">
                        <i class="bi {{ $task->category->icon }} me-1"></i>{{ $task->category->name }}
                    </span>
                @endif
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if ($task->status !== \App\Enums\TaskStatus::Completed)
                <form method="POST" action="{{ route('tasks.complete', $task) }}">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check2 me-1"></i> Complete
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('tasks.reopen', $task) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reopen
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('tasks.duplicate', $task) }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-copy me-1"></i> Duplicate
                </button>
            </form>

            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm">Edit</a>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
            <form
                method="POST"
                action="{{ route('tasks.destroy', $task) }}"
                data-confirm-delete
                data-confirm-title="Delete task?"
                data-confirm-text="This will move the task to trash."
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h6 text-secondary text-uppercase">Description</h2>
                    <p class="mb-0">{{ $task->description ?: 'No description provided.' }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 text-secondary text-uppercase">Notes</h2>
                    <div class="mb-0" style="white-space: pre-wrap;">{{ $task->notes ?: 'No notes yet.' }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 text-secondary text-uppercase mb-3">Schedule</h2>
                    <dl class="row mb-0 small">
                        <dt class="col-5">Due date</dt>
                        <dd class="col-7">{{ $task->due_date?->format('M j, Y') ?: '—' }}</dd>

                        <dt class="col-5">Due time</dt>
                        <dd class="col-7">
                            {{ $task->due_time ? \Illuminate\Support\Str::of($task->due_time)->substr(0, 5) : '—' }}
                        </dd>

                        <dt class="col-5">Reminder</dt>
                        <dd class="col-7">{{ $task->reminder_at?->format('M j, Y H:i') ?: '—' }}</dd>

                        <dt class="col-5">Completed</dt>
                        <dd class="col-7">{{ $task->completed_at?->format('M j, Y H:i') ?: '—' }}</dd>

                        <dt class="col-5">Created</dt>
                        <dd class="col-7">{{ $task->created_at->format('M j, Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
