@extends('layouts.app')

@section('title', __('Notifications').' — '.config('app.name'))
@section('page-title', __('Notifications'))

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3 reveal">
        <div>
            <h1 class="h2 mb-1">{{ __('Notifications') }}</h1>
            <p class="text-secondary mb-0">{{ __('Reminders, due today, overdue, and completed tasks.') }}</p>
        </div>

        @if (auth()->user()->unreadNotifications->isNotEmpty())
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-soft btn-soft-primary">
                    <i class="bi bi-check2-all"></i> {{ __('Mark all as read') }}
                </button>
            </form>
        @endif
    </div>

    @if ($notifications->isEmpty())
        <x-empty-state
            title="{{ __('You\'re all caught up') }}"
            message="{{ __('No notifications yet. Reminders and due-date alerts will appear here.') }}"
            icon="bi-bell"
        />
    @else
        <div class="d-grid gap-3">
            @foreach ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $taskTitle = $data['task_title'] ?? null;
                    $title = match ($data['type'] ?? null) {
                        'reminder' => $taskTitle
                            ? __('Reminder: :title', ['title' => $taskTitle])
                            : ($data['title'] ?? __('Notification')),
                        'due_today' => $taskTitle
                            ? __('Due today: :title', ['title' => $taskTitle])
                            : ($data['title'] ?? __('Notification')),
                        'overdue' => $taskTitle
                            ? __('Overdue: :title', ['title' => $taskTitle])
                            : ($data['title'] ?? __('Notification')),
                        'completed' => $taskTitle
                            ? __('Completed: :title', ['title' => $taskTitle])
                            : ($data['title'] ?? __('Notification')),
                        default => $data['title'] ?? __('Notification'),
                    };
                    $message = match ($data['type'] ?? null) {
                        'reminder' => $taskTitle
                            ? __('Your reminder for ":title" is due.', ['title' => $taskTitle])
                            : ($data['message'] ?? ''),
                        'due_today' => $taskTitle
                            ? __('":title" is due today.', ['title' => $taskTitle])
                            : ($data['message'] ?? ''),
                        'overdue' => $taskTitle
                            ? __('":title" is overdue.', ['title' => $taskTitle])
                            : ($data['message'] ?? ''),
                        'completed' => $taskTitle
                            ? __('You completed ":title".', ['title' => $taskTitle])
                            : ($data['message'] ?? ''),
                        default => $data['message'] ?? '',
                    };
                @endphp
                <div class="panel notification-card {{ $notification->read_at ? '' : 'is-unread' }} reveal">
                    <div class="d-flex justify-content-between gap-3">
                        <div class="d-flex gap-3">
                            @unless ($notification->read_at)
                                <span class="notification-dot" aria-hidden="true"></span>
                            @endunless
                            <div>
                                <div class="fw-bold mb-1">{{ $title }}</div>
                                @if ($message !== '')
                                    <div class="small text-secondary mb-2">{{ $message }}</div>
                                @endif
                                <div class="small text-secondary">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="action-group">
                            @if (! empty($data['task_id']))
                                <a href="{{ route('tasks.show', $data['task_id']) }}" class="btn btn-soft btn-sm">
                                    {{ __('Open') }}
                                </a>
                            @endif
                            @unless ($notification->read_at)
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-soft btn-soft-primary btn-sm">
                                        {{ __('Mark read') }}
                                    </button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
@endsection
