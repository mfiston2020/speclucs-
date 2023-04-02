<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalPrescription extends Model
{
    use HasFactory;

    protected $fillable=[
        'medication',
        'strength',
        'route',
        'dosage',
        't_dosage',
        'frequency',
        'duration'
    ];
}
