<?php

namespace App\Models\Doctor;

use CodeIgniter\Model;

class DoctorScheduleModel extends Model
{
    protected $table            = 'doctor_schedules';
    protected $primaryKey       = 'schedule_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Set true jika tabel punya kolom deleted_at
    protected $protectFields    = true;

    // Kolom yang diizinkan untuk di-input/update
    protected $allowedFields    = [
        'doctor_id',
        'day',
        'shift',      // Pagi, Siang, Malam
        'start_time',
        'end_time',
        'quota',
        'status',     // available, full, leave
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false; // Set true jika ada created_at & updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'doctor_id'  => 'required|integer',
        'day'        => 'required',
        'shift'      => 'required',
        'start_time' => 'required',
        'end_time'   => 'required',
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Custom Method: Mengambil jadwal lengkap dengan Nama Dokter & Poli
     * Digunakan untuk tampilan Admin atau User agar lebih mudah dibaca.
     */
    public function getJadwalLengkap()
    {
        return $this->select('doctor_schedules.*, users.full_name as doctor_name, departments.name as poli_name')
            ->join('doctors', 'doctors.doctor_id = doctor_schedules.doctor_id')
            ->join('users', 'users.user_id = doctors.user_id')
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->orderBy('FIELD(day, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")') // Urutkan hari dengan benar
            ->orderBy('start_time', 'ASC')
            ->findAll();
    }

    /**
     * Custom Method: Mengambil jadwal spesifik per dokter
     */
    public function getJadwalByDokter($doctorId)
    {
        return $this->where('doctor_id', $doctorId)
                    ->where('status', 'available')
                    ->findAll();
    }
}