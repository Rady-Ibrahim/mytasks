@php
    $dueTime = old('due_time', isset($task) && $task->due_time ? \Illuminate\Support\Str::of($task->due_time)->substr(0, 5)->toString() : '');
    $reminderAt = old('reminder_at', isset($task) && $task->reminder_at ? $task->reminder_at->format('Y-m-d\TH:i') : '');
@endphp

<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input
        id="title"
        type="text"
        name="title"
        value="{{ old('title', $task->title ?? '') }}"
        class="form-control @error('title') is-invalid @enderror"
        required
        maxlength="255"
    >
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea
        id="description"
        name="description"
        rows="3"
        class="form-control @error('description') is-invalid @enderror"
    >{{ old('description', $task->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="category_id" class="form-label">Category</label>
        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">No category</option>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    @selected((string) old('category_id', $task->category_id ?? '') === (string) $category->id)
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="priority" class="form-label">Priority</label>
        <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror" required>
            @foreach ($priorities as $priority)
                <option
                    value="{{ $priority->value }}"
                    @selected(old('priority', $task->priority->value ?? 'medium') === $priority->value)
                >
                    {{ $priority->label() }}
                </option>
            @endforeach
        </select>
        @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach ($statuses as $status)
                <option
                    value="{{ $status->value }}"
                    @selected(old('status', $task->status->value ?? 'pending') === $status->value)
                >
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label for="due_date" class="form-label">Due date</label>
        <input
            id="due_date"
            type="date"
            name="due_date"
            value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
            class="form-control @error('due_date') is-invalid @enderror"
        >
        @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="due_time" class="form-label">Due time</label>
        <input
            id="due_time"
            type="time"
            name="due_time"
            value="{{ $dueTime }}"
            class="form-control @error('due_time') is-invalid @enderror"
        >
        @error('due_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="reminder_at" class="form-label">Reminder</label>
        <input
            id="reminder_at"
            type="datetime-local"
            name="reminder_at"
            value="{{ $reminderAt }}"
            class="form-control @error('reminder_at') is-invalid @enderror"
        >
        @error('reminder_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-4">
    <label for="notes" class="form-label">Notes</label>
    <textarea
        id="notes"
        name="notes"
        rows="5"
        class="form-control @error('notes') is-invalid @enderror"
        placeholder="Write detailed notes for this task..."
    >{{ old('notes', $task->notes ?? '') }}</textarea>
    @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
