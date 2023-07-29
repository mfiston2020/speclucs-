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
        'sent_to_lab'
    ];


    function SoldProduct()
    {
        return $this->hasMany(SoldProduct::class);
    }

    function client()
    {
        return $this->belongsTo(Customer::class);
    }
}
