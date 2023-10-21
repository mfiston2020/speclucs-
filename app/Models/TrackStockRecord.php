<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackStockRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'current_stock',
        'incoming',
        'change',
        'operation',
        'reason',
        'type',
        'status',
        'company_id'
    ];

    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
