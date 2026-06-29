<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    //
    protected $fillable = [
        'purchase_order_id',
        'item',
        'material',
        'satuan',
        'qty',
        'unit_price',
        'subtotal',
        'delivery_date',
        'notes',
        'company',
        'pic_buyer',
        'supplier',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
