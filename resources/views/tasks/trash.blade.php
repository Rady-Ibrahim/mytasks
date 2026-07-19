@extends('layouts.app')

@section('title', __('Trash').' — '.config('app.name'))
@section('page-title', __('Trash'))

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3 reveal">
        <div>
            <h1 class="h2 mb-1">{{ __('Trash') }}</h1>
            <p class="text-secondary mb-0">{{ __('Restore deleted tasks or remove them permanently.') }}</p>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn btn-soft">
            <i class="bi bi-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i> {{ __('Back to tasks') }}
        </a>
    </div>

    @if ($tasks->isEmpty())
        <x-empty-state
            title="{{ __('Trash is empty') }}"
            message="{{ __('Deleted tasks will appear here.') }}"
            icon="bi-trash"
        />
    @else
        <div class="panel overflow-hidden reveal">
            <div class="table-responsive">
                <table class="table table-modern table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Task') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Deleted') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="fw-semibold">{{ $task->title }}</td>
                                <td>
                                    @if ($task->category)
                                        {{ __($task->category->name) }}
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td>{{ $task->deleted_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <div class="action-group">
                                        <form method="POST" action="{{ route('tasks.restore', $task) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-soft btn-soft-success btn-sm">
                                                <i class="bi bi-arrow-counterclockwise"></i> {{ __('Restore') }}
                                            </button>
                                        </form>
                                        <form
                                            method="POST"
                                            action="{{ route('tasks.force-delete', $task) }}"
                                            data-confirm-delete
                                            data-confirm-title="{{ __('Permanently delete?') }}"
                                            data-confirm-text="{{ __('This cannot be undone.') }}"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft btn-soft-danger btn-sm">
                                                <i class="bi bi-trash"></i> {{ __('Delete forever') }}
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
