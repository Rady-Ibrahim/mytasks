<?php

namespace App\Http\Middleware;

use App\Enums\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        app()->setLocale($locale->value);
        Carbon::setLocale($locale->value);

        return $next($request);
    }

    private function resolveLocale(Request $request): Locale
    {
        $sessionLocale = Locale::tryFrom((string) $request->session()->get('locale', ''));

        if ($sessionLocale instanceof Locale) {
            return $sessionLocale;
        }

        $userLocale = $request->user()?->locale;

        if ($userLocale instanceof Locale) {
            $request->session()->put('locale', $userLocale->value);

            return $userLocale;
        }

        return Locale::tryFrom((string) config('app.locale', 'ar')) ?? Locale::Arabic;
    }
}
