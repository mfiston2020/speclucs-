<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable =   [
        'client_id',
        'client_name',
        'phone',
        'tin_number',
        'gender',
        'dateOfBirth',
        'status',
        'received_by_lab',
        'sent_to_lab',
        'set_price',
        'sent_to_seller',
        'payment_approval',
        'sent_to_supplier',
        'received_by_seller',
        'received_by_patient',
        'receive_from_supplier',
    ];

    function SoldProduct()
    {
        return $this->hasMany(SoldProduct::class,'invoice_id');
    }

    function company(){
        return $this->belongsTo(CompanyInformation::class,'company_id');
    }

    function supplier(){
        return $this->belongsTo(CompanyInformation::class,'supplier_id');
    }

    function client()
    {
        return $this->belongsTo(Customer::class);
    }

    function unavailableProducts()
    {
        return $this->hasMany(UnavailableProduct::class);
    }

    function isAsuranceInvoice(){
        return $this->hasOne(InsuranceSumaryInvoices::class);
    }

    function hasbeeninvoiced(){
        return InsuranceSumaryInvoices::where('invoice_id',$this->id)->exists();
    }
}
