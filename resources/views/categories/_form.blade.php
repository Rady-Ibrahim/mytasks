@php
    $selectedIcon = old('icon', $category->icon ?? 'bi-tag');
    $selectedColor = old('color', $category->color ?? '#0d6efd');
@endphp

<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input
        id="name"
        type="text"
        name="name"
        value="{{ old('name', $category->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
        required
        maxlength="100"
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="color" class="form-label">Color</label>
    <div class="input-group" style="max-width: 12rem;">
        <input
            id="color"
            type="color"
            name="color"
            value="{{ $selectedColor }}"
            class="form-control form-control-color @error('color') is-invalid @enderror"
            title="Choose color"
        >
        <span class="input-group-text font-monospace small">{{ $selectedColor }}</span>
    </div>
    @error('color')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label">Icon</label>
    <div class="row g-2">
        @foreach ($iconOptions as $icon)
            <div class="col-4 col-sm-3 col-md-2">
                <input
                    type="radio"
                    class="btn-check"
                    name="icon"
                    id="icon-{{ $icon }}"
                    value="{{ $icon }}"
                    autocomplete="off"
                    @checked($selectedIcon === $icon)
                >
                <label class="btn btn-outline-secondary w-100" for="icon-{{ $icon }}">
                    <i class="bi {{ $icon }}"></i>
                </label>
            </div>
        @endforeach
    </div>
    @error('icon')
        <div class="text-danger small mt-2">{{ $message }}</div>
    @enderror
</div>
