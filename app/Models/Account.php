<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    protected $table = 'account_table';

    protected $fillable = [
        'user_id',
        'phone',
        'company',
        'position',
        'address',
        'city',
        'zip',
        'fax',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
