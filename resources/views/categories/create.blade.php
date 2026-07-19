@extends('layouts.app')

@section('title', 'New Category — '.config('app.name'))
@section('page-title', 'New Category')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Create category</h1>

                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        @include('categories._form')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save category</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
