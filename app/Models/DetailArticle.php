<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'dept_code',
        'requirement',
        'check_by',
        'date',
        'remark',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the article that owns the detailArticle.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who checked the detail.
     */
    public function checkBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'check_by');
    }
}
