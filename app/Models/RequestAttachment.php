<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    //
    protected $fillable = ['request_id','prefix_path','file_path','document_name'];

    //relation
    public function request()
    {
        return $this->belongsTo(RequestProject::class,'request_id');
    }
}
