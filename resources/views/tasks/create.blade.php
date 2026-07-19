@extends('layouts.app')

@section('title', 'New Task — '.config('app.name'))
@section('page-title', 'New Task')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Create task</h1>

                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        @include('tasks._form')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save task</button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
