<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderInternal extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'item',
        'material',
        'spesifikasi',
        'article',
        'qty',
        'unit_price',
        'subtotal',
        'delivery_date',
        'supplier',
        'po_no',
        'pic_buyer',
        'company_buyer',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    /**
     * Relasi ke Purchase Order Header
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Relasi ke Kontrak Tinjauan Per-Item Spesifik (Terbaru)
     */
    public function contract()
    {
        return $this->hasOne(Contract::class, 'purchase_order_internal_id')->latestOfMany('id');
    }

    /**
     * Relasi ke Seluruh Kontrak Tinjauan Per-Item
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'purchase_order_internal_id');
    }
}