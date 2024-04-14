<?php

namespace App\Models;

use App\Models\Manager\StatementInvoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceStatement extends Model
{
    use HasFactory;

    function customer(){
        return $this->belongsTo(CompanyInformation::class,'customer_id');
    }

    function statementInvoices(){
        return $this->hasMany(StatementInvoice::class,'statement_id');
    }
}
