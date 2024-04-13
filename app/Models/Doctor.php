<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "department_id",
        "job",
        "monday",
        "tuesday",
        "wednesday",
        "thursday",
        "friday",
        "saturday",
        "sunday"
    ];

    protected $hidden = ['pivot'];

    public function services() {
        return $this->belongsToMany(Service::class, 
        'doctor_service_links', 
        'doctor_id', 'service_id');
    }

}
