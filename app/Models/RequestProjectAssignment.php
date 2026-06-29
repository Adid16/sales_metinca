<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestProjectAssignment extends Model
{
    //
    protected $fillable = ['request_project_id', 'sales_id'];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }
}
