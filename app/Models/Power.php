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

    function type(){
        return $this->belongsTo(LensType::class);
    }

    function chromatic(){
        return $this->belongsTo(PhotoChromatics::class,'chromatics_id');
    }

    function coating(){
        return $this->belongsTo(PhotoCoating::class,'coating_id');
    }

    function index(){
        return $this->belongsTo(PhotoIndex::class,'index_id');
    }
}
