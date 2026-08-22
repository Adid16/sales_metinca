<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'max_negotiation_limit',
                'value' => '3',
                'description' => 'Batas maksimal counter negosiasi harga per Quotation.',
            ],
            [
                'key' => 'max_amandement_limit',
                'value' => '2',
                'description' => 'Batas maksimal pengajuan amandemen per item PO.',
            ],
            [
                'key' => 'min_price_margin_percentage',
                'value' => '0',
                'description' => 'Persentase margin minimum harga jual di bawah harga master artikel (0 = harga master jadi batas bawah).',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
