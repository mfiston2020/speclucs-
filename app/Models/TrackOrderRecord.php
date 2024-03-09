<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackOrderRecord extends Model
{
    use HasFactory;

    protected $fillable=[
        'status',
        'user_id',
        'invoice_id',
    ];

    function doneBy(){
        return $this->belongsTo(User::class,'user_id');
    }
}
