<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CurrencyService;
use App\Services\LanguageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationAndCurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_switch_languages(): void
    {
        $languages = ['en', 'ja', 'de', 'zh', 'ar', 'id'];

        foreach ($languages as $lang) {
            $response = $this->get(route('set.locale', $lang));
            $response->assertRedirect();
            $response->assertSessionHas('locale', $lang);
        }
    }

    public function test_user_can_switch_currencies(): void
    {
        $currencies = ['USD', 'EUR', 'JPY', 'SGD', 'IDR'];

        foreach ($currencies as $curr) {
            $response = $this->get(route('set.currency', $curr));
            $response->assertRedirect();
            $response->assertSessionHas('currency', $curr);
        }
    }

    public function test_currency_conversion_and_formatting(): void
    {
        $idrAmount = 1585000; // 100 USD

        // Format in IDR
        $idrFormatted = CurrencyService::format($idrAmount, 'IDR');
        $this->assertStringContainsString('Rp', $idrFormatted);
        $this->assertStringContainsString('1.585.000', $idrFormatted);

        // Format in USD
        $usdFormatted = CurrencyService::format($idrAmount, 'USD');
        $this->assertStringContainsString('$', $usdFormatted);
        $this->assertStringContainsString('100.00', $usdFormatted);

        // Format in EUR
        $eurFormatted = CurrencyService::format(1720000, 'EUR');
        $this->assertStringContainsString('€', $eurFormatted);
        $this->assertStringContainsString('100.00', $eurFormatted);

        // Format in JPY
        $jpyFormatted = CurrencyService::format(10500, 'JPY');
        $this->assertStringContainsString('¥', $jpyFormatted);
        $this->assertStringContainsString('100', $jpyFormatted);
    }

    public function test_dashboard_renders_language_and_currency_dropdowns(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'divisi' => 'sales',
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'en', 'currency' => 'USD'])
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('googleLangDropdown');
        $response->assertSee('Google Translate');
    }
}
