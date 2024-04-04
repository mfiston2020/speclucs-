<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'supplier_id',
        'request_from',
        'status',
    ];

    function supplier(){
        return $this->belongsTo(CompanyInformation::class,'supplier_id');
    }

    function requestCompany(){
        return $this->belongsTo(CompanyInformation::class,'request_from');
    }

    function supplyingState(){
        return SupplyRequest::where('supplier_id',$this->supplier_id)->where('request_from',userInfo()->company_id)->pluck('status')->first();
    }
}
