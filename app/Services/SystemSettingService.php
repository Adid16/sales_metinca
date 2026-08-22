<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Models\Quotation;

class SystemSettingService
{
    /**
     * Ambil batas maksimal counter negosiasi harga per Quotation.
     */
    public static function maxNegotiationLimit(): int
    {
        return (int) system_setting('max_negotiation_limit', 3);
    }

    /**
     * Ambil batas maksimal pengajuan amandemen per item PO.
     */
    public static function maxAmendmentLimit(): int
    {
        return (int) system_setting('max_amandement_limit', 2);
    }

    /**
     * Ambil persentase margin minimum harga jual.
     */
    public static function minPriceMarginPercentage(): float
    {
        return (float) system_setting('min_price_margin_percentage', 0);
    }

    /**
     * Set / update nilai system setting berdasarkan key.
     */
    public static function set(string $key, $value): void
    {
        system_setting([$key => $value]);
    }

    /**
     * Manager Override: Tambahkan kuota negosiasi tambahan pada Quotation tertentu.
     */
    public static function overrideNegotiationLimit(int $quotationId, int $additionalQuota = 1): bool
    {
        $quotation = Quotation::find($quotationId);
        if (!$quotation) {
            return false;
        }

        $quotation->increment('negotiation_override_quota', $additionalQuota);
        return true;
    }

    /**
     * Hitung total kuota negosiasi efektif untuk Quotation tertentu.
     * = max_negotiation_limit (global) + negotiation_override_quota (per-quotation)
     */
    public static function effectiveNegotiationLimit(Quotation $quotation): int
    {
        return self::maxNegotiationLimit() + (int) ($quotation->negotiation_override_quota ?? 0);
    }
}
