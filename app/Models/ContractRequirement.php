<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractRequirement extends Model
{
    //
    protected $fillable = ['contract_id','requirement_from','requirement','requirement_value'];

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id');
    }
}
