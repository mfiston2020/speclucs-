<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailableProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost',
        'price',
        'location',
        'supplier_id',
        'product_id'

    ];

    function product(){
        return $this->belongsTo(Product::class);
    }

    function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
