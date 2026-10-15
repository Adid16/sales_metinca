<?php

if (!function_exists('format_currency')) {
    /**
     * Format a numerical price (stored in base IDR) according to current active currency.
     */
    function format_currency(float|int|null $amountInIdr, ?string $targetCurrency = null, bool $showOriginalIdr = false): string
    {
        return \App\Services\CurrencyService::format($amountInIdr, $targetCurrency, $showOriginalIdr);
    }
}

if (!function_exists('current_currency')) {
    /**
     * Get current currency code (e.g. IDR, USD, EUR, JPY, SGD).
     */
    function current_currency(): string
    {
        return \App\Services\CurrencyService::getCurrentCurrency();
    }
}

if (!function_exists('current_currency_symbol')) {
    /**
     * Get current currency symbol (e.g. Rp, $, €, ¥, S$).
     */
    function current_currency_symbol(): string
    {
        $code = \App\Services\CurrencyService::getCurrentCurrency();
        return \App\Services\CurrencyService::CURRENCIES[$code]['symbol'] ?? 'Rp';
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get current active language code.
     */
    function current_locale(): string
    {
        return \App\Services\LanguageService::getCurrentLocale();
    }
}
