<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use App\Services\LanguageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    /**
     * Switch application language.
     */
    public function setLocale(Request $request, string $locale): RedirectResponse
    {
        if (array_key_exists($locale, LanguageService::LANGUAGES)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }

    /**
     * Switch application currency.
     */
    public function setCurrency(Request $request, string $currency): RedirectResponse
    {
        $currencyUpper = strtoupper($currency);
        if (array_key_exists($currencyUpper, CurrencyService::CURRENCIES)) {
            session(['currency' => $currencyUpper]);
        }

        return redirect()->back();
    }
}
