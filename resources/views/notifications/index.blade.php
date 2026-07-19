@extends('layouts.app')

@section('title', 'Notifications — '.config('app.name'))
@section('page-title', 'Notifications')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Notifications</h1>
            <p class="text-secondary mb-0">Reminders, due today, overdue, and completed tasks.</p>
        </div>

        @if (auth()->user()->unreadNotifications->isNotEmpty())
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">Mark all as read</button>
            </form>
        @endif
    </div>

    @if ($notifications->isEmpty())
        <x-empty-state
            title="You're all caught up"
            message="No notifications yet. Reminders and due-date alerts will appear here."
            icon="bi-bell"
        />
    @else
        <div class="list-group shadow-sm">
            @foreach ($notifications as $notification)
                <div class="list-group-item {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                            <div class="small text-secondary mb-1">{{ $notification->data['message'] ?? '' }}</div>
                            <div class="small text-secondary">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-2">
                            @if (! empty($notification->data['task_id']))
                                <a href="{{ route('tasks.show', $notification->data['task_id']) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                            @endif
                            @unless ($notification->read_at)
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark read</button>
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
