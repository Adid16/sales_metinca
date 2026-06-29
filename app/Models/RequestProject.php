<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestProject extends Model
{
    //
    protected $table = 'request_projects';
    protected $fillable = [
        'email','subject','message','name','phone','company','customer_id'
    ];

    //relation
    // public function sales()
    // {
    //     return $this->belongsTo(User::class,'sales_id');
    // }

    public function quotation()
    {
        return $this->hasOne(\App\Models\Quotation::class, 'request_id');
    }

    public function attachments()
    {
        return $this->hasMany(RequestAttachment::class,'request_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    // public function requestProjects()
    // {
    //     return $this->hasMany(RequestProject::class, 'sales_id');
    // }
    
    public function assignment()
    {
        return $this->hasOne(\App\Models\RequestProjectAssignment::class);
    }
    
}
