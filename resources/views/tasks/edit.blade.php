@extends('layouts.app')

@section('title', __('Edit task').' — '.config('app.name'))
@section('page-title', __('Edit task'))

@section('content')
    <div class="row justify-content-center reveal">
        <div class="col-lg-8">
            <div class="panel p-4 p-md-5">
                <h1 class="h4 mb-4 fw-bold">{{ __('Edit task') }}</h1>

                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PUT')
                    @include('tasks._form')
                    <div class="toolbar-actions">
                        <button type="submit" class="btn btn-primary">{{ __('Update task') }}</button>
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-soft">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
