<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'article_id',
        'item',
        'qty',
        'price',
        'original_price',
        'negotiated_price',
        'floor_price',
        'is_below_floor_price',
    ];

    protected $casts = [
        'qty'                  => 'integer',
        'price'                => 'float',
        'original_price'       => 'float',
        'negotiated_price'     => 'float',
        'floor_price'          => 'float',
        'is_below_floor_price' => 'boolean',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
