<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierNotify extends Model
{
    use HasFactory;

    protected $table='supplier_notifies';

    protected $fillable=[
        'notification',
    ];

    function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
