<?php

namespace App\Controllers\Pendaftaran;

use App\Controllers\BaseController;

class AppointmentController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Fungsi 1: Dipanggil JavaScript saat Poli dipilih
    public function getDoctorsByDept($deptId)
    {
        // Mengambil data dokter + nama dari tabel users berdasarkan poli
        $query = $this->db->table('doctors')
            ->select('doctors.doctor_id, users.full_name')
            ->join('users', 'users.user_id = doctors.user_id')
            ->where('doctors.department_id', $deptId)
            ->get();

        // Mengembalikan format JSON (Bukan View)
        return $this->response->setJSON($query->getResultArray());
    }

    // Fungsi 2: Dipanggil JavaScript saat Dokter dipilih
    public function getSchedulesByDoctor($doctorId)
    {
        // Mengambil jadwal yang statusnya 'available'
        $query = $this->db->table('doctor_schedules')
            ->where('doctor_id', $doctorId)
            ->where('status', 'available')
            ->orderBy('day', 'ASC') // Urutkan hari
            ->get();

        return $this->response->setJSON($query->getResultArray());
    }
}