@extends('layouts.app')

@section('title', 'Categories — '.config('app.name'))
@section('page-title', 'Categories')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Categories</h1>
            <p class="text-secondary mb-0">Organize tasks with colors and icons.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New category
        </a>
    </div>

    @if ($categories->isEmpty())
        <x-empty-state
            title="No categories yet"
            message="Create your first category to start organizing tasks."
            icon="bi-tags"
        >
            <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">Create category</a>
        </x-empty-state>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Color</th>
                            <th>Icon</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <span
                                        class="d-inline-flex align-items-center justify-content-center rounded-circle me-2"
                                        style="width: 2rem; height: 2rem; background-color: {{ $category->color }}20; color: {{ $category->color }};"
                                    >
                                        <i class="bi {{ $category->icon }}"></i>
                                    </span>
                                    <span class="fw-semibold">{{ $category->name }}</span>
                                </td>
                                <td>
                                    <span class="badge border" style="background-color: {{ $category->color }};">&nbsp;</span>
                                    <code class="small ms-1">{{ $category->color }}</code>
                                </td>
                                <td><code>{{ $category->icon }}</code></td>
                                <td class="text-end">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('categories.destroy', $category) }}"
                                        class="d-inline"
                                        data-confirm-delete
                                        data-confirm-title="Delete category?"
                                        data-confirm-text="This will soft-delete the category."
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    @endif
@endsection
