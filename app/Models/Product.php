<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = [
        'location',
        'cost',
        'price',
        'stock',
        'company_id',
        'description',
        'category_id',
        'fitting_cost',
        'product_name',
        'deffective_stock',
        'slug_name',
        'transaction_id',
    ];

    // protected $hidden = [
    //     'product_name',
    // ];

    function category()
    {
        return $this->BelongsTo(Category::class);
    }

    function productTrack()
    {
        return $this->hasMany(TrackStockRecord::class, 'product_id');
    }

    function power()
    {
        return $this->hasOne(Power::class);
    }

    function soldProducts()
    {
        return $this->hasMany(SoldProduct::class, 'product_id');
    }

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    function stockRecords()
    {
        return $this->hasMany(TrackStockRecord::class);
    }

    function costOfGoodSold($year)
    {
        return $this->soldProducts->where('product_id', $this->id)->where('created_at', $year)->sum('quantity');
    }

    function fifo($date)
    {
        return $this->soldProducts->where('product_id', $this->id)->where('created_at', $date)->where('type', 'rm')->sum('incoming');
    }
}
