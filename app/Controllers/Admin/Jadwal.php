<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Doctor\DoctorScheduleModel;
use App\Models\Doctor\DoctorModel;

class Jadwal extends BaseController
{
    protected $scheduleModel;
    protected $doctorModel;
    protected $db;

    public function __construct()
    {
        $this->scheduleModel = new DoctorScheduleModel();
        $this->doctorModel   = new DoctorModel();
        $this->db            = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil data jadwal + Nama Dokter + Nama Poli
        $jadwal = $this->scheduleModel
            ->select('doctor_schedules.*, users.full_name as doctor_name, departments.name as poli_name')
            ->join('doctors', 'doctors.doctor_id = doctor_schedules.doctor_id')
            ->join('users', 'users.user_id = doctors.user_id')
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->orderBy('doctor_schedules.day', 'ASC')
            ->findAll();

        return view('admin/jadwal', [
            'title'  => 'Manajemen Jadwal Dokter',
            'jadwal' => $jadwal
        ]);
    }

    public function create()
    {
        // Ambil daftar dokter untuk dropdown
        $doctors = $this->doctorModel
            ->select('doctors.doctor_id, users.full_name, departments.name as poli_name')
            ->join('users', 'users.user_id = doctors.user_id')
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->findAll();

        return view('admin/jadwal_create', [
            'title'   => 'Tambah Jadwal Baru',
            'doctors' => $doctors
        ]);
    }

    public function store()
    {
        // Validasi Input
        if (!$this->validate([
            'doctor_id'  => 'required',
            'day'        => 'required',
            'shift'      => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan ke Database
        $this->scheduleModel->save([
            'doctor_id'  => $this->request->getPost('doctor_id'),
            'day'        => $this->request->getPost('day'),
            'shift'      => $this->request->getPost('shift'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time'   => $this->request->getPost('end_time'),
            'quota'      => $this->request->getPost('quota') ?? 20, // Default 20 jika kosong
            'status'     => 'available'
        ]);

        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Berhasil Ditambahkan');
    }

    public function delete($id)
    {
        $this->scheduleModel->delete($id);
        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Berhasil Dihapus');
    }
}
