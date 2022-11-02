<?php

            namespace App\Models;

            use Illuminate\Database\Eloquent\Model;

            class Doctor extends Model
            {
                protected $guarded = [];
                public function doctorAppoinment()
                {
                    return $this->belongsTo(DoctorAppointment::class);
                }
                public function available_date()
                {
                    return $this->hasMany(AvailableDate::class);
                 }
            }

        ?>
