<div class="card border-0 shadow-sm h-100">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi {{ $icon }} text-primary"></i>
            <h2 class="h6 mb-0">{{ $title }}</h2>
        </div>

        @if ($tasks->isEmpty())
            <p class="text-secondary small mb-0">{{ $empty }}</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($tasks as $task)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-decoration-none">
                                {{ $task->title }}
                            </a>
                            <div class="small text-secondary">
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
</div>
