<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailableProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'coating_id',
        'index_id',
        'chromatic_id',
        'cost',
        'price',
        'location',
        'supplier_id',
        'product_id'

    ];

    function type(){
        return $this->belongsTo(LensType::class,'type_id');
    }

    function coating(){
        return $this->belongsTo(PhotoCoating::class,'coating_id');
    }

    function uindex(){
        return $this->belongsTo(PhotoIndex::class,'index_id');
    }

    function uchromatic(){
        return $this->belongsTo(PhotoChromatics::class,'chromatic_id');
    }

    function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    function lookforproduct($product){
        return Product::where('id',$product)->first();
    }

    function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
