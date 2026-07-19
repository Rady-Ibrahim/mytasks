@extends('layouts.app')

@section('title', 'Dashboard — '.config('app.name'))
@section('page-title', 'Dashboard')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-secondary mb-0">Your personal task workspace is ready.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-right me-1"></i> Log out
            </button>
        </form>
    </div>

    <x-empty-state
        title="Dashboard coming next"
        message="Statistics, today's tasks, and productivity summaries will appear in Phase 8."
        icon="bi-speedometer2"
    />
@endsection
