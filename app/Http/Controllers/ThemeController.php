<?php

namespace App\Http\Controllers;

use App\Enums\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $theme = Theme::tryFrom((string) $request->input('theme'))
            ?? ($request->user()->theme ?? Theme::Light)->toggle();

        $request->user()->forceFill([
            'theme' => $theme,
        ])->save();

        return back()->with('success', 'Theme updated to '.$theme->label().' mode.');
    }
}
