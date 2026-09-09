<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    protected $fillable = [
        'customer_id',
        'request_id',
        'quotation_no',
        'date_expired',
        'company_name',
        'description',
        'material',
        'quantity_required_pcs',
        'die_cavities',
        'grade_type',
        'form_of_supply',
        'qty_per_mould_pcs',
        'pattern_wax',
        'metal',
        'soluble_wax',
        'ceramic',
        'runner_wax',
        'total_raw_material_cost',
        'injection',
        'cut_off',
        'cleaning',
        'scut_off',
        'assembly',
        'finishing',
        'dipping',
        'heat_treatment',
        'dewaxing',
        'straight',
        'burnout',
        'repair',
        'melting',
        'blasting',
        'knockout',
        'inspect',
        'w_blast',
        'casting_weight',
        'total_minutes_per_mould',
        'total_minutes_mould',
        'machining_add',
        'x_ray',
        'crack_det',
        'polish',
        'total_minutes_mould_add',
        'total_add',
        'wax',
        'fixed_overheads_usd',
        'straightening',
        'scrap_usd',
        'machining_fix',
        'total_mould_cost',
        'piece_price_usd',
        'sub_contracting',
        'director_comment_approval',
        'attachments',
        'status',
        'is_below_floor_price',
        'manager_approval_status',
        'approved_by_manager_id',
        'manager_approval_note',
        'manager_approved_at',
        'sent_date',
        'accepted_date',
        'po_date',
        'notes',
        'item',
        'qty',
        'price',
        'target_delivery_date',
        'payment_terms',
        'negotiation_override_quota',
    ];

    protected $casts = [
        'is_below_floor_price' => 'boolean',
        'manager_approved_at'  => 'datetime',
        'date_expired'         => 'date',
        'sent_date'            => 'date',
        'accepted_date'        => 'date',
        'po_date'              => 'date',
        'target_delivery_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function approvedByManager()
    {
        return $this->belongsTo(User::class, 'approved_by_manager_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'quotation_id');
    }

    public function canSend()
    {
        return $this->status === 'created';
    }

    public function purchaseOrder()
    {
        return $this->hasOne(\App\Models\PurchaseOrder::class, 'quotation_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestProject::class, 'request_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function negotiates()
    {
        return $this->hasMany(\App\Models\Negotiate::class);
    }
}
