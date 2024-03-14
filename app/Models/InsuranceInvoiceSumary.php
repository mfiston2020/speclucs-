<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceInvoiceSumary extends Model
{
    use HasFactory;

    protected $fillable=[
        'company_id',
        'invoice_id',
        'user_id',
        'insurance_id',
        'status',
    ];
}
