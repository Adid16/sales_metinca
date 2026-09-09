<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Negotiate extends Model
{
    protected $table = 'negotiate';
 
    protected $fillable = [
        'quotation_id',
        'user_id',
        'from_customer',
        'message',
        'negotiated_total',
        'payment_terms',
        'target_delivery_date',
        'support_document',
        'action',
        'negotiated_items',
        'requires_manager_approval',
        'manager_approval_status',
        'manager_approval_note',
        'manager_approved_by',
        'manager_approved_at',
        'floor_price_snapshot',
    ];
 
    protected $casts = [
        'from_customer'             => 'boolean',
        'requires_manager_approval' => 'boolean',
        'negotiated_items'          => 'array',
        'floor_price_snapshot'      => 'array',
        'target_delivery_date'      => 'date',
        'manager_approved_at'       => 'datetime',
    ];
 
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_approved_by');
    }
}