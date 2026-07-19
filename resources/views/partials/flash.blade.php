@if (session('success') || session('error') || session('status'))
    @php
        $toastMessage = session('success') ?? session('error') ?? session('status');
        $toastClass = session('error') ? 'text-bg-danger' : 'text-bg-success';
    @endphp

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div
            id="app-toast"
            class="toast align-items-center {{ $toastClass }} border-0"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
            data-bs-delay="4000"
        >
            <div class="d-flex">
                <div class="toast-body">{{ $toastMessage }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="fw-semibold mb-1">Please fix the following:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
