<?php

namespace App\Services;

class LanguageService
{
    /**
     * Supported major world languages.
     */
    public const LANGUAGES = [
        'id' => [
            'code' => 'id',
            'label' => 'ID',
            'name' => 'Bahasa Indonesia',
            'native' => 'Indonesia',
            'flag' => '🇮🇩',
        ],
        'en' => [
            'code' => 'en',
            'label' => 'EN',
            'name' => 'English (US/UK)',
            'native' => 'English',
            'flag' => '🇬🇧',
        ],
        'ja' => [
            'code' => 'ja',
            'label' => 'JA',
            'name' => 'Japanese',
            'native' => '日本語',
            'flag' => '🇯🇵',
        ],
        'de' => [
            'code' => 'de',
            'label' => 'DE',
            'name' => 'German',
            'native' => 'Deutsch',
            'flag' => '🇩🇪',
        ],
        'zh' => [
            'code' => 'zh',
            'label' => 'ZH',
            'name' => 'Chinese Simplified',
            'native' => '简体中文',
            'flag' => '🇨🇳',
        ],
        'ar' => [
            'code' => 'ar',
            'label' => 'AR',
            'name' => 'Arabic',
            'native' => 'العربية',
            'flag' => '🇸🇦',
        ],
    ];

    /**
     * Get current locale from session or config.
     */
    public static function getCurrentLocale(): string
    {
        $loc = session('locale', config('app.locale', 'id'));
        return isset(self::LANGUAGES[$loc]) ? $loc : 'id';
    }

    /**
     * Get list of all supported languages.
     */
    public static function getAll(): array
    {
        return self::LANGUAGES;
    }
}
