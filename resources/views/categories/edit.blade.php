@extends('layouts.app')

@section('title', __('Edit category').' — '.config('app.name'))
@section('page-title', __('Edit category'))

@section('content')
    <div class="row justify-content-center reveal">
        <div class="col-lg-7">
            <div class="panel p-4 p-md-5">
                <h1 class="h4 mb-4 fw-bold">{{ __('Edit category') }}</h1>

                <form method="POST" action="{{ route('categories.update', $category) }}">
                    @csrf
                    @method('PUT')
                    @include('categories._form')
                    <div class="toolbar-actions">
                        <button type="submit" class="btn btn-primary">{{ __('Update category') }}</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-soft">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
