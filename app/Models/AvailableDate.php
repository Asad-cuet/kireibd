<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableDate extends Model
{
    use HasFactory;
    protected $table='available_date';
    protected $fillable=[
        'doctor_id',
        'date',
        'time',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function doctor()  //making relationship
                {
                     return $this->belongsTo(Doctor::class,'doctor_id','id');
                }
}
