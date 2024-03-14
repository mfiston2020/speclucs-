<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceSumaryInvoices extends Model
{
    use HasFactory;

    protected $fillable=[
        'status',
        'user_id',
        'company_id',
        'invoice_id',
        'insurance_id',
        'total_amount',
        'credit_amount',
        'insurance_amount',
        'invoice_sumary_id',
    ];

    function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
