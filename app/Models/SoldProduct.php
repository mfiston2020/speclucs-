<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldProduct extends Model
{
    use HasFactory;

    function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function insurance(){
        return $this->belongsTo(Insurance::class);
    }

    function hasLens():bool{
        if ($this->product()->where('category_id','1')->exists()) {
            return true;
        }else{
            return false;
        }
    }

    function hasFrame():bool{
        if ($this->product()->where('category_id','2')->exists()) {
            return true;
        }else{
            return false;
        }
    }

    function hasAccessories():bool{
        if ($this->product()->whereNotIn('category_id',['2,','1'])->exists()) {
            return true;
        }else{
            return false;
        }
    }

    // function totalLensAmount($invoice){
    //     return $this->product()->where('invoice_id',$invoice)->sum('unit_price');
    // }

    // function totalFrameAmount(){
    //     return $this->SoldProduct()->with('product',function($q){
    //         $q->where('category_id','2');
    //     })->where('invoice_id',$this->id)->sum('unit_price');
    // }

    // function totalAccessoriesAmount(){
    //     return $this->SoldProduct()->with('product',function($q){
    //         $q->where('category_id','2');
    //     })->where('invoice_id',$this->id)->sum('unit_price');
    // }
}
