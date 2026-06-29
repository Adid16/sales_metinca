<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderInternal extends Model
{
    //
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
        'delivery_date'=>'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
