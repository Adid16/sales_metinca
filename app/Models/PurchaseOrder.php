<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'delivery_request',
        'quotation_id',
        'attachment',
        'po_no',
        'status',
        'status_order'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function internals()
    {
        return $this->hasMany(\App\Models\PurchaseOrderInternal::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public static function newAmandement()
    {
        $year = now()->year;

        $last = self::whereYear('created_at', $year)
            ->where('po_no', 'like', "PO-$year-%")
            ->orderByDesc('id')
            ->first();

        $next = $last
            ? ((int) substr($last->po_no, -3)) + 1
            : 1;

        return sprintf('PO-%d-%03d', $year, $next);
    }

    public function amendments()
    {
        return $this->hasMany(PoAmendment::class, 'purchase_order_id');
    }

    public function contracts()
    {
        // Relasi jamak (hasMany)
        return $this->hasMany(Contract::class, 'order_no', 'po_no')->orderBy('amandement_no', 'asc');
    }

    // =========================================================================
    // TAMBAHKAN RELASI SINGULAR INI AGAR EAGER LOADING 'contract' TIDAK ERROR
    // =========================================================================
    public function contract()
    {
        return $this->hasOne(Contract::class, 'order_no', 'po_no');
    }

    public function internalContracts()
    {
        return $this->hasManyThrough(Contract::class, PurchaseOrderInternal::class, 'purchase_order_id', 'purchase_order_internal_id');
    }

    public function getSalesPicAttribute()
    {
        if ($this->quotation && $this->quotation->request && $this->quotation->request->assignment && $this->quotation->request->assignment->sales) {
            return $this->quotation->request->assignment->sales;
        }
        return null;
    }
}