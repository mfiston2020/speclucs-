<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = [
        'cost',
        'price',
        'stock',
        'company_id',
        'description',
        'category_id',
        'fitting_cost',
        'product_name',
        'deffective_stock',
    ];

    protected $hidden = [
        'product_name',
    ];

    function category()
    {
        return $this->BelongsTo(Category::class);
    }

    function power()
    {
        return $this->hasOne(Power::class);
    }

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
