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
    ];
 
    protected $casts = [
        'from_customer'        => 'boolean',
        'negotiated_items'     => 'array',
        'target_delivery_date' => 'date',
    ];
 
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}