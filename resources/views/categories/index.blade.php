@extends('layouts.app')

@section('title', __('Categories').' — '.config('app.name'))
@section('page-title', __('Categories'))

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3 reveal">
        <div>
            <h1 class="h2 mb-1">{{ __('Categories') }}</h1>
            <p class="text-secondary mb-0">{{ __('Organize tasks with colors and icons.') }}</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> {{ __('New category') }}
        </a>
    </div>

    @if ($categories->isEmpty())
        <x-empty-state
            title="{{ __('No categories yet') }}"
            message="{{ __('Create your first category to start organizing tasks.') }}"
            icon="bi-tags"
        >
            <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">{{ __('Create category') }}</a>
        </x-empty-state>
    @else
        <div class="row g-3">
            @foreach ($categories as $category)
                <div class="col-md-6 col-xl-4 reveal reveal-delay-{{ min($loop->iteration, 5) }}">
                    <div class="panel category-card">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <span
                                    class="category-card-icon"
                                    style="background-color: {{ $category->color }}22; color: {{ $category->color }};"
                                >
                                    <i class="bi {{ $category->icon }}"></i>
                                </span>
                                <div>
                                    <div class="fw-bold">{{ __($category->name) }}</div>
                                    <div class="small text-secondary">{{ $category->color }}</div>
                                </div>
                            </div>
                            <span class="rounded-circle border" style="width: 1rem; height: 1rem; background: {{ $category->color }};"></span>
                        </div>

                        <div class="action-group justify-content-start">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-soft btn-sm">
                                <i class="bi bi-pencil"></i> {{ __('Edit') }}
                            </a>
                            <form
                                method="POST"
                                action="{{ route('categories.destroy', $category) }}"
                                data-confirm-delete
                                data-confirm-title="{{ __('Delete category?') }}"
                                data-confirm-text="{{ __('This will soft-delete the category.') }}"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-soft btn-soft-danger btn-sm">
                                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    @endif
@endsection
