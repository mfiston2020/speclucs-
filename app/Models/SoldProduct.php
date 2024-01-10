<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldProduct extends Model
{
    use HasFactory;

    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function insurance(){
        return $this->belongsTo(Insurance::class);
    }

    function hasLens(){
        if ($this->product()->where('category_id','2')->exists()) {
            return true;
        }else{
            return false;
        }
    }

    function hasAccessories(){
        if ($this->product()->whereNotIn('category_id',['2,','1'])->exists()) {
            return true;
        }else{
            return false;
        }
    }
}
