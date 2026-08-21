<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'customer_id',
        'quotation_id',
        'purchase_order_internal_id', // <-- 1. Tambahkan ini di $fillable
        'order_no',
        'contract_no',
        'status',
        'part_no',
        'part_name',
        'article_id',
        'amandement_no',
        'alasan_amandemen',
        'alasan_penolakan',
        'others_comment',
        'po_pdf',
        'sales_approver',
        'sales_approved_at',
        'ppc_approver',
        'ppc_approved_at',
        'quality_approver',
        'quality_approved_at',
        'dev_engineering_approver',
        'dev_engineering_approved_at',
    ];

    public function requirements()
    {
        return $this->hasMany(ContractRequirement::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    // <-- 2. Tambahkan fungsi relasi ini di bawah relasi article()
    public function internalItem()
    {
        return $this->belongsTo(PurchaseOrderInternal::class, 'purchase_order_internal_id');
    }

    public function purchaseOrderInternal()
    {
        return $this->belongsTo(PurchaseOrderInternal::class, 'purchase_order_internal_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_no', 'po_no');
    }

    public function isDone()
    {
        if ($this->sales_approver != null && $this->ppc_approver != null && $this->quality_approver != null && $this->dev_engineering_approver != null) {
            $this->status = 'done';
            $this->save();
        }
    }

    protected static function booted()
    {
        static::creating(function ($contract) {

            if (!empty($contract->contract_no)) {
                return;
            }

            $year = now()->year;

            $last = self::whereYear('created_at', $year)
                ->where('contract_no', 'like', "CT-$year-%")
                ->orderByDesc('id')
                ->first();

            $next = $last
                ? ((int) substr($last->contract_no, -3)) + 1
                : 1;

            $contract->contract_no = sprintf('CT-%d-%03d', $year, $next);
        });
    }
}