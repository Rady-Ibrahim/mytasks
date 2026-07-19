@php
    $currentLocale = \App\Enums\Locale::tryFrom(app()->getLocale()) ?? \App\Enums\Locale::Arabic;
    $nextLocale = $currentLocale->toggle();
@endphp

<form method="POST" action="{{ route('locale.update') }}" class="d-inline">
    @csrf
    <input type="hidden" name="locale" value="{{ $nextLocale->value }}">
    <button
        type="submit"
        class="{{ $buttonClass ?? 'topnav-icon' }}"
        title="{{ __('Switch to :language', ['language' => $nextLocale->label()]) }}"
        data-testid="locale-toggle"
        aria-label="{{ __('Switch to :language', ['language' => $nextLocale->label()]) }}"
    >
        <span class="locale-toggle-label">{{ $nextLocale->shortLabel() }}</span>
    </button>
</form>
