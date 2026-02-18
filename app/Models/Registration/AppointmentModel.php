<?php

namespace App\Models\Registration;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'patient_id',
        'schedule_id',
        'doctor_id',
        'appointment_date',
        'status'
    ];
}
