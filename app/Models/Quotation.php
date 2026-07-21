<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    //1. definisi nama tabel
    protected $table = 'quotations';

    //2. daftar isi kolom
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
        'customer_id',
        'status',
        'sent_date',
        'accepted_date',
        'po_date',
        'notes',
        'item',
        'qty',
        'target_delivery_date',
        'payment_terms',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class,'quotation_id');
    }

    public function canSend()
    {
        return $this->status === 'created';
    }

    public function purchaseOrder()
    {
        return $this->hasOne(\App\Models\PurchaseOrder::class, 'quotation_id');
    }

    // protected static function booted()
    // {
    //     static::creating(function ($quotation) {

    //         $year = now()->year;

    //         $last = self::whereYear('created_at', $year)
    //             ->where('quotation_no', 'like', "QT-$year-%")
    //             ->orderByDesc('id')
    //             ->first();

    //         $next = $last
    //             ? ((int) substr($last->quotation_no, -3)) + 1
    //             : 1;

    //         $quotation->quotation_no = sprintf('QT-%d-%03d', $year, $next);
    //     });
    // }

    public function request()
    {
        return $this->belongsTo(RequestProject::class,'request_id');
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
