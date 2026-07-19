<div class="panel filter-panel mb-4 p-3 p-md-4 reveal">
    <form method="GET" action="{{ route('tasks.index') }}" class="row g-3 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label for="q" class="form-label">{{ __('Search') }}</label>
            <input
                id="q"
                type="search"
                name="q"
                value="{{ $filters['q'] }}"
                class="form-control"
                placeholder="{{ __('Title, description, category...') }}"
            >
        </div>

        <div class="col-lg-2 col-md-3 col-sm-6">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <select id="status" name="status" class="form-select">
                <option value="">{{ __('All') }}</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 col-md-3 col-sm-6">
            <label for="priority" class="form-label">{{ __('Priority') }}</label>
            <select id="priority" name="priority" class="form-select">
                <option value="">{{ __('All') }}</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}" @selected($filters['priority'] === $priority->value)>
                        {{ $priority->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <label for="category_id" class="form-label">{{ __('Category') }}</label>
            <select id="category_id" name="category_id" class="form-select">
                <option value="">{{ __('All') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($filters['category_id'] === (string) $category->id)>
                        {{ __($category->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <label for="due" class="form-label">{{ __('Due') }}</label>
            <select id="due" name="due" class="form-select">
                <option value="">{{ __('Any date') }}</option>
                <option value="today" @selected($filters['due'] === 'today')>{{ __('Today') }}</option>
                <option value="tomorrow" @selected($filters['due'] === 'tomorrow')>{{ __('Tomorrow') }}</option>
                <option value="this_week" @selected($filters['due'] === 'this_week')>{{ __('This week') }}</option>
                <option value="this_month" @selected($filters['due'] === 'this_month')>{{ __('This month') }}</option>
            </select>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="sort" class="form-label">{{ __('Sort by') }}</label>
            <div class="input-group">
                <select id="sort" name="sort" class="form-select">
                    <option value="created_at" @selected($filters['sort'] === 'created_at')>{{ __('Created') }}</option>
                    <option value="due_date" @selected($filters['sort'] === 'due_date')>{{ __('Due date') }}</option>
                    <option value="priority" @selected($filters['sort'] === 'priority')>{{ __('Priority') }}</option>
                    <option value="title" @selected($filters['sort'] === 'title')>{{ __('Title') }}</option>
                </select>
                <select name="direction" class="form-select" style="max-width: 7.5rem;">
                    <option value="desc" @selected($filters['direction'] === 'desc')>{{ __('Desc') }}</option>
                    <option value="asc" @selected($filters['direction'] === 'asc')>{{ __('Asc') }}</option>
                </select>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 toolbar-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> {{ __('Apply') }}
            </button>
            <a href="{{ route('tasks.index') }}" class="btn btn-soft">{{ __('Reset') }}</a>
        </div>
    </form>
</div>
