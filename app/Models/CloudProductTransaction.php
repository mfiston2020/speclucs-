<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloudProductTransaction extends Model
{
    protected $fillable=[
        'eye',
        'status',
        'product_id',
        'company_id',
        'vision_center',
        'transaction_id',
    ];

    function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
