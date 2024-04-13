<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorServiceLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'service_id'
    ];

    public $timestamps = false;

}
