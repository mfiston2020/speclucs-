<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    use HasFactory;

    protected $fillable =   [
        'country_id',
        'company_name',
        'company_phone',
        'company_email',
    ];

    function country(){
        return $this->belongsTo(Country::class,'country_id');
    }

    function supplying(){
        return $this->hasMany(SupplyRequest::class,'supplier_id');
    }

    function countSupplyRequests(){
        return SupplyRequest::where('supplier_id',getuserCompanyInfo()->id)->where('status','pending')->count();
    }
}
