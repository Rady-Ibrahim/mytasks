@props([
    'title' => 'Nothing here yet',
    'message' => 'Content will appear once you add some data.',
    'icon' => 'bi-inbox',
])

<div {{ $attributes->merge(['class' => 'empty-state panel']) }}>
    <div class="display-6 text-secondary mb-3">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h2 class="h5 mb-2">{{ $title }}</h2>
    <p class="mb-0">{{ $message }}</p>
    {{ $slot }}
</div>
