<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Supported world currencies.
     * Base currency for calculations is IDR (Rupiah).
     */
    public const CURRENCIES = [
        'IDR' => [
            'code' => 'IDR',
            'symbol' => 'Rp',
            'name' => 'Indonesian Rupiah',
            'flag' => '🇮🇩',
            'rate_from_idr' => 1,
            'decimals' => 0,
            'decimal_sep' => ',',
            'thousand_sep' => '.',
            'symbol_first' => true,
        ],
        'USD' => [
            'code' => 'USD',
            'symbol' => '$',
            'name' => 'US Dollar',
            'flag' => '🇺🇸',
            'rate_from_idr' => 15850, // 1 USD = Rp 15,850
            'decimals' => 2,
            'decimal_sep' => '.',
            'thousand_sep' => ',',
            'symbol_first' => true,
        ],
        'EUR' => [
            'code' => 'EUR',
            'symbol' => '€',
            'name' => 'Euro (EU)',
            'flag' => '🇪🇺',
            'rate_from_idr' => 17200, // 1 EUR = Rp 17,200
            'decimals' => 2,
            'decimal_sep' => '.',
            'thousand_sep' => ',',
            'symbol_first' => true,
        ],
        'JPY' => [
            'code' => 'JPY',
            'symbol' => '¥',
            'name' => 'Japanese Yen',
            'flag' => '🇯🇵',
            'rate_from_idr' => 105, // 1 JPY = Rp 105
            'decimals' => 0,
            'decimal_sep' => '.',
            'thousand_sep' => ',',
            'symbol_first' => true,
        ],
        'SGD' => [
            'code' => 'SGD',
            'symbol' => 'S$',
            'name' => 'Singapore Dollar',
            'flag' => '🇸🇬',
            'rate_from_idr' => 12100, // 1 SGD = Rp 12,100
            'decimals' => 2,
            'decimal_sep' => '.',
            'thousand_sep' => ',',
            'symbol_first' => true,
        ],
    ];

    /**
     * Get current active currency code from session.
     */
    public static function getCurrentCurrency(): string
    {
        $curr = session('currency', 'IDR');
        return isset(self::CURRENCIES[$curr]) ? $curr : 'IDR';
    }

    /**
     * Convert an amount stored in IDR to the target currency.
     */
    public static function convert(float|int|null $amountInIdr, ?string $targetCurrency = null): float
    {
        $targetCurrency = strtoupper($targetCurrency ?: self::getCurrentCurrency());
        if (!isset(self::CURRENCIES[$targetCurrency])) {
            $targetCurrency = 'IDR';
        }

        $amount = (float) ($amountInIdr ?? 0);
        if ($targetCurrency === 'IDR') {
            return $amount;
        }

        $rate = self::CURRENCIES[$targetCurrency]['rate_from_idr'];
        return $rate > 0 ? ($amount / $rate) : $amount;
    }

    /**
     * Format an amount stored in IDR into the active currency format.
     */
    public static function format(float|int|null $amountInIdr, ?string $targetCurrency = null, bool $showOriginalIdr = false): string
    {
        $targetCurrency = strtoupper($targetCurrency ?: self::getCurrentCurrency());
        if (!isset(self::CURRENCIES[$targetCurrency])) {
            $targetCurrency = 'IDR';
        }

        $curr = self::CURRENCIES[$targetCurrency];
        $converted = self::convert($amountInIdr, $targetCurrency);
        $formattedNumber = number_format($converted, $curr['decimals'], $curr['decimal_sep'], $curr['thousand_sep']);

        $result = $curr['symbol_first']
            ? "{$curr['symbol']} {$formattedNumber}"
            : "{$formattedNumber} {$curr['symbol']}";

        if ($showOriginalIdr && $targetCurrency !== 'IDR') {
            $idrFormatted = number_format((float) ($amountInIdr ?? 0), 0, ',', '.');
            $result .= " (Rp {$idrFormatted})";
        }

        return $result;
    }

    /**
     * Get list of all supported currencies.
     */
    public static function getAll(): array
    {
        return self::CURRENCIES;
    }
}
