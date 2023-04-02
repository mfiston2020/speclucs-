<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Power extends Model
{
    use HasFactory;

    protected $fillable=[
        'eye',
        'add',
        'axis',
        'sphere',
        'type_id',
        'cylinder',
        'index_id',
        'company_id',
        'product_id',
        'coating_id',
        'chromatics_id',
    ];
}
