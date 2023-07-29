<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldProduct extends Model
{
    use HasFactory;

    function productSold()
    {
        return $this->belongsTo(Product::class);
    }
}
