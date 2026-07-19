@extends('layouts.app')

@section('title', 'Edit Task — '.config('app.name'))
@section('page-title', 'Edit Task')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Edit task</h1>

                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PUT')
                        @include('tasks._form')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update task</button>
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
