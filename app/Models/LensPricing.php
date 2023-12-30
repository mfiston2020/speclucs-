<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LensPricing extends Model
{
    use HasFactory;

    protected $fillable=[
        'company_id',
        'type_id',
        'coating_id',
        'index_id',
        'chromatic_id',
        'sphere_from',
        'sphere_to',
        'cylinder_from',
        'cylinder_to',
        'addition_from',
        'addition_to',
    ];

    function LensType(){
        return $this->belongsTo(LensType::class,'type_id');
    }

    function index(){
        return $this->belongsTo(PhotoIndex::class,'index_id');
    }

    function chromatics(){
        return $this->belongsTo(PhotoChromatics::class,'chromatic_id');
    }

    function coating(){
        return $this->belongsTo(PhotoCoating::class,'coating_id');
    }
}
