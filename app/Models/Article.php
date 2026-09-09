<?php

namespace App\Models;

use App\Services\SystemSettingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_part_no',
        'article_no',
        'part_name',
        'index_no',
        'berat',
        'die_no',
        'material',
        'drawing_no',
        'drawing_rev',
        'effective_date',
        'customer_id',
        'lokasi_pengerjaan',
        'remark',
        
        // Kolom Harga
        'casting_price',
        'machining_price',
        'price',
        'price_list',
        'floor_price',
        'bottom_price',
        'pdf_attachment',
    ];

    protected $casts = [
        'price'           => 'float',
        'price_list'      => 'float',
        'floor_price'     => 'float',
        'bottom_price'    => 'float',
        'casting_price'   => 'float',
        'machining_price' => 'float',
    ];

    /**
     * Relasi ke model User/Customer
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Dapatkan harga price list efektif (fallback ke price jika price_list null)
     */
    public function getEffectivePriceListAttribute(): float
    {
        return (float) ($this->price_list ?? $this->price ?? 0);
    }

    /**
     * Dapatkan batas bawah harga (Floor Price / Bottom Price)
     */
    public function getEffectiveFloorPriceAttribute(): float
    {
        if ($this->floor_price !== null && $this->floor_price > 0) {
            return (float) $this->floor_price;
        }

        if ($this->bottom_price !== null && $this->bottom_price > 0) {
            return (float) $this->bottom_price;
        }

        $basePrice = $this->effective_price_list;
        if ($basePrice > 0) {
            $margin = SystemSettingService::minPriceMarginPercentage();
            return round($basePrice * (1 - ($margin / 100)), 2);
        }

        return 0.0;
    }
}