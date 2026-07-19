@extends('layouts.app')

@section('title', $task->title.' — '.config('app.name'))
@section('page-title', __('Task Details'))

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-start gap-3 reveal">
        <div>
            <h1 class="h2 mb-2">{{ $task->title }}</h1>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge {{ $task->priority->badgeClass() }}">{{ $task->priority->label() }}</span>
                <span class="badge {{ $task->status->badgeClass() }}">{{ $task->status->label() }}</span>
                @if ($task->category)
                    <span class="badge border" style="color: {{ $task->category->color }};">
                        <i class="bi {{ $task->category->icon }}"></i> {{ __($task->category->name) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="toolbar-actions">
            @if ($task->status !== \App\Enums\TaskStatus::Completed)
                <form method="POST" action="{{ route('tasks.complete', $task) }}">
                    @csrf
                    <button type="submit" class="btn btn-soft btn-soft-success btn-sm">
                        <i class="bi bi-check2"></i> {{ __('Complete') }}
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('tasks.reopen', $task) }}">
                    @csrf
                    <button type="submit" class="btn btn-soft btn-soft-primary btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> {{ __('Reopen') }}
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('tasks.duplicate', $task) }}">
                @csrf
                <button type="submit" class="btn btn-soft btn-sm">
                    <i class="bi bi-copy"></i> {{ __('Duplicate') }}
                </button>
            </form>

            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm">{{ __('Edit') }}</a>
            <a href="{{ route('tasks.index') }}" class="btn btn-soft btn-sm">{{ __('Back') }}</a>
            <form
                method="POST"
                action="{{ route('tasks.destroy', $task) }}"
                data-confirm-delete
                data-confirm-title="{{ __('Delete task?') }}"
                data-confirm-text="{{ __('This will move the task to trash.') }}"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-soft btn-soft-danger btn-sm">{{ __('Delete') }}</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="panel p-4 mb-4 reveal reveal-delay-1">
                <h2 class="h6 text-secondary text-uppercase">{{ __('Description') }}</h2>
                <p class="mb-0">{{ $task->description ?: __('No description provided.') }}</p>
            </div>

            <div class="panel p-4 reveal reveal-delay-2">
                <h2 class="h6 text-secondary text-uppercase">{{ __('Notes') }}</h2>
                <div class="mb-0" style="white-space: pre-wrap;">{{ $task->notes ?: __('No notes yet.') }}</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel p-4 reveal reveal-delay-3">
                <h2 class="h6 text-secondary text-uppercase mb-3">{{ __('Schedule') }}</h2>
                <dl class="row mb-0 small">
                    <dt class="col-5">{{ __('Due date') }}</dt>
                    <dd class="col-7">{{ $task->due_date?->translatedFormat('d M Y') ?: '—' }}</dd>

                    <dt class="col-5">{{ __('Due time') }}</dt>
                    <dd class="col-7">
                        {{ $task->due_time ? \Illuminate\Support\Str::of($task->due_time)->substr(0, 5) : '—' }}
                    </dd>

                    <dt class="col-5">{{ __('Reminder') }}</dt>
                    <dd class="col-7">{{ $task->reminder_at?->translatedFormat('d M Y H:i') ?: '—' }}</dd>

                    <dt class="col-5">{{ __('Completed') }}</dt>
                    <dd class="col-7">{{ $task->completed_at?->translatedFormat('d M Y H:i') ?: '—' }}</dd>

                    <dt class="col-5">{{ __('Created') }}</dt>
                    <dd class="col-7">{{ $task->created_at->translatedFormat('d M Y H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
