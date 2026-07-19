<?php

namespace App\Http\Controllers;

use App\Enums\Locale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', Rule::in(Locale::values())],
        ]);

        $locale = Locale::from($validated['locale']);

        $request->session()->put('locale', $locale->value);

        if ($request->user()) {
            $request->user()->forceFill([
                'locale' => $locale,
            ])->save();
        }

        return back()->with('success', __('Language updated to :language.', ['language' => $locale->label()]));
    }
}
