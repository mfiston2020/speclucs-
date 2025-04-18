<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloudProductTransaction extends Model
{
    protected $fillable=[
        'product_id',
        'transaction_id'
    ];
}
