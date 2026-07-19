<div class="panel task-panel h-100 p-3 p-md-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary" style="width: 2.25rem; height: 2.25rem;">
            <i class="bi {{ $icon }}"></i>
        </span>
        <h2 class="h6 mb-0 fw-bold">{{ $title }}</h2>
    </div>

    @if ($tasks->isEmpty())
        <div class="empty-state py-4">
            <i class="bi {{ $icon }} display-6 d-block mb-2"></i>
            <p class="text-secondary small mb-0">{{ $empty }}</p>
        </div>
    @else
        <ul class="list-unstyled mb-0">
            @foreach ($tasks as $task)
                <li class="task-row d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-decoration-none">
                            {{ $task->title }}
                        </a>
                        <div class="small text-secondary mt-1">
                            <span class="badge {{ $task->status->badgeClass() }}">{{ $task->status->label() }}</span>
                            @if ($task->due_date)
                                · {{ $task->due_date->format('M j') }}
                            @endif
                            @if ($task->category)
                                · {{ $task->category->name }}
                            @endif
                        </div>
                    </div>
                    <span class="badge {{ $task->priority->badgeClass() }}">{{ $task->priority->label() }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
