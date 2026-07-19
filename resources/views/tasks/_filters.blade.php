<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label for="q" class="form-label">Search</label>
                <input
                    id="q"
                    type="search"
                    name="q"
                    value="{{ $filters['q'] }}"
                    class="form-control"
                    placeholder="Title, description, category..."
                >
            </div>

            <div class="col-lg-2 col-md-3 col-sm-6">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-3 col-sm-6">
                <label for="priority" class="form-label">Priority</label>
                <select id="priority" name="priority" class="form-select">
                    <option value="">All</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->value }}" @selected($filters['priority'] === $priority->value)>
                            {{ $priority->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <label for="category_id" class="form-label">Category</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($filters['category_id'] === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <label for="due" class="form-label">Due</label>
                <select id="due" name="due" class="form-select">
                    <option value="">Any date</option>
                    <option value="today" @selected($filters['due'] === 'today')>Today</option>
                    <option value="tomorrow" @selected($filters['due'] === 'tomorrow')>Tomorrow</option>
                    <option value="this_week" @selected($filters['due'] === 'this_week')>This week</option>
                    <option value="this_month" @selected($filters['due'] === 'this_month')>This month</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <label for="sort" class="form-label">Sort by</label>
                <div class="input-group">
                    <select id="sort" name="sort" class="form-select">
                        <option value="created_at" @selected($filters['sort'] === 'created_at')>Created</option>
                        <option value="due_date" @selected($filters['sort'] === 'due_date')>Due date</option>
                        <option value="priority" @selected($filters['sort'] === 'priority')>Priority</option>
                        <option value="title" @selected($filters['sort'] === 'title')>Title</option>
                    </select>
                    <select name="direction" class="form-select" style="max-width: 7rem;">
                        <option value="desc" @selected($filters['direction'] === 'desc')>Desc</option>
                        <option value="asc" @selected($filters['direction'] === 'asc')>Asc</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i> Apply
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>
