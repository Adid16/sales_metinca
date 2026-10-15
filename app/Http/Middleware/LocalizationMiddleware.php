<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Manage Language / Locale
        $locale = session('locale', config('app.locale', 'id'));
        if (!array_key_exists($locale, LanguageService::LANGUAGES)) {
            $locale = 'id';
            session(['locale' => 'id']);
        }
        App::setLocale($locale);

        // 2. Manage Active Currency
        $currency = session('currency', 'IDR');
        if (!array_key_exists($currency, CurrencyService::CURRENCIES)) {
            $currency = 'IDR';
            session(['currency' => 'IDR']);
        }

        // 3. Share globally with Blade views
        View::share('activeLocale', $locale);
        View::share('activeLocaleInfo', LanguageService::LANGUAGES[$locale] ?? LanguageService::LANGUAGES['id']);
        View::share('allLanguages', LanguageService::LANGUAGES);

        View::share('activeCurrency', $currency);
        View::share('activeCurrencyInfo', CurrencyService::CURRENCIES[$currency] ?? CurrencyService::CURRENCIES['IDR']);
        View::share('allCurrencies', CurrencyService::CURRENCIES);

        return $next($request);
    }
}
