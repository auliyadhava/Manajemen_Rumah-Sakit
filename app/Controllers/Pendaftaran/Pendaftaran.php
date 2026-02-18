<?php

namespace App\Controllers\Pendaftaran;

use App\Controllers\BaseController;
use App\Models\Registration\AppointmentModel;
use App\Models\Registration\QueueModel;

class Pendaftaran extends BaseController
{
    protected $appointmentModel;
    protected $queueModel;
    protected $db;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->queueModel       = new QueueModel();
        $this->db               = \Config\Database::connect();
    }

    /* =======================
       DASHBOARD PENDAFTARAN
       ======================= */
    public function index()
    {
        $today = date('Y-m-d');

        /* ======================
           SUMMARY TOTAL
        ====================== */

        // Update: Menggunakan 'appointment_date'
        $totalPendaftaran = $this->appointmentModel
            ->where('appointment_date', $today)
            ->countAllResults();

        $totalWaiting = $this->appointmentModel
            ->where('status', 'waiting')
            ->where('appointment_date', $today)
            ->countAllResults();

        // Update: Join ke schedules untuk filter tanggal
        $totalQueue = $this->queueModel
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->where('appointments.appointment_date', $today)
            ->countAllResults();

        /* ======================
           5 PENDAFTARAN TERBARU
        ====================== */
        // Update: Join bertingkat (Appointments -> Schedules -> Doctors -> Departments)
        $pendaftaranTerbaru = $this->appointmentModel
            ->select('
                appointments.status,
                appointments.appointment_date,
                users.full_name AS patient_name,
                doctor_users.full_name AS doctor_name,
                departments.name AS department_name,
                doctor_schedules.shift
            ')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id') // User Pasien
            ->join('doctor_schedules', 'doctor_schedules.schedule_id = appointments.schedule_id')
            ->join('doctors', 'doctors.doctor_id = doctor_schedules.doctor_id')
            ->join('users as doctor_users', 'doctor_users.user_id = doctors.user_id') // User Dokter
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->orderBy('appointments.appointment_id', 'DESC')
            ->limit(5)
            ->findAll();

        /* ======================
           5 ANTRIAN TERBARU
        ====================== */
        $antrianTerbaru = $this->queueModel
            ->select('
                queues.queue_number,
                queues.status,
                users.full_name AS patient_name,
                departments.name AS department_name
            ')
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('doctor_schedules', 'doctor_schedules.schedule_id = appointments.schedule_id')
            ->join('doctors', 'doctors.doctor_id = doctor_schedules.doctor_id')
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->where('appointments.appointment_date', $today)
            ->orderBy('queues.queue_number', 'DESC')
            ->limit(5)
            ->findAll();

        return view('pendaftaran/dashboard', [
            'title'            => 'Dashboard Pendaftaran',
            'totalPendaftaran' => $totalPendaftaran,
            'totalWaiting'     => $totalWaiting,
            'totalQueue'       => $totalQueue,
            'pendaftaranTerbaru' => $pendaftaranTerbaru,
            'antrianTerbaru'   => $antrianTerbaru,
        ]);
    }

    /* =======================
       LIST PENDAFTARAN PASIEN
       ======================= */
    public function pasien()
    {
        // Menampilkan daftar pasien beserta Jadwal Sif Dokter yang dipilih
        $dataPasien = $this->appointmentModel
            ->select('
                appointments.appointment_id,
                appointments.status,
                appointments.appointment_date,
                appointments.queue_number AS booking_queue,
                users.full_name AS patient_name,
                doctor_users.full_name AS doctor_name,
                departments.name AS department_name,
                doctor_schedules.day,
                doctor_schedules.shift,
                doctor_schedules.start_time,
                doctor_schedules.end_time
            ')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('doctor_schedules', 'doctor_schedules.schedule_id = appointments.schedule_id')
            ->join('doctors', 'doctors.doctor_id = doctor_schedules.doctor_id')
            ->join('users as doctor_users', 'doctor_users.user_id = doctors.user_id')
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->orderBy('appointments.appointment_id', 'DESC')
            ->findAll();

        return view('pendaftaran/pendaftaran_pasien', [
            'title'      => 'Pendaftaran Pasien',
            'dataPasien' => $dataPasien
        ]);
    }

    /* =======================
       KONFIRMASI PASIEN
       ======================= */
    public function konfirmasi($appointment_id)
    {
        // 1. Ambil data appointment dengan detail relasi
        $appointment = $this->appointmentModel
            ->select('
                appointments.appointment_id,
                appointments.status,
                appointments.appointment_date,
                appointments.schedule_id,
                users.full_name,
                departments.name AS department_name
            ')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('doctor_schedules', 'doctor_schedules.schedule_id = appointments.schedule_id')
            ->join('doctors', 'doctors.doctor_id = doctor_schedules.doctor_id')
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->where('appointments.appointment_id', $appointment_id)
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($appointment['status'] === 'confirmed') {
            return redirect()->back()->with('warning', 'Pasien sudah dikonfirmasi');
        }

        // 2. Update Status Appointment
        $this->appointmentModel->update($appointment_id, [
            'status' => 'confirmed'
        ]);

        /* =======================
           LOGIKA ANTRIAN
           ======================= */
        $scheduleId = $appointment['schedule_id'];
        $today      = date('Y-m-d');

        // Cari nomor antrian terakhir di tabel QUEUES untuk jadwal yang sama hari ini
        $lastQueue = $this->queueModel
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->where('appointments.schedule_id', $scheduleId) // Filter per jadwal dokter
            ->where('appointments.appointment_date', $today)
            ->orderBy('queues.queue_number', 'DESC')
            ->first();

        $queueNumber = $lastQueue ? ((int)$lastQueue['queue_number'] + 1) : 1;

        // Insert ke tabel queues (Antrian Fisik/Panggilan)
        $this->queueModel->insert([
            'appointment_id' => $appointment_id,
            'queue_number'   => $queueNumber,
            'status'         => 'waiting' // Status antrian fisik: Menunggu dipanggil
        ]);

        // Simpan Tiket Flashdata
        session()->setFlashdata('queue_ticket', [
            'queue_number'  => str_pad($queueNumber, 3, '0', STR_PAD_LEFT),
            'full_name'     => $appointment['full_name'],
            'department'    => $appointment['department_name'],
            'schedule_date' => $appointment['appointment_date']
        ]);

        return redirect()->to('/pendaftaran/pasien')
            ->with('success', 'Pasien dikonfirmasi. Nomor Antrian: ' . $queueNumber);
    }

    /* =======================
       LIST ANTRIAN HARI INI
       ======================= */
    public function antrian()
    {
        $today = date('Y-m-d');

        $dataAntrian = $this->queueModel
            ->select('
                queues.queue_number,
                queues.status,
                users.full_name AS patient_name,
                doctor_users.full_name AS doctor_name,
                departments.name AS department_name,
                doctor_schedules.shift
            ')
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            // Relasi lengkap
            ->join('doctor_schedules', 'doctor_schedules.schedule_id = appointments.schedule_id')
            ->join('doctors', 'doctors.doctor_id = doctor_schedules.doctor_id')
            ->join('users as doctor_users', 'doctor_users.user_id = doctors.user_id')
            ->join('departments', 'departments.department_id = doctors.department_id')
            ->where('appointments.appointment_date', $today)
            ->orderBy('queues.queue_number', 'ASC')
            ->findAll();

        return view('pendaftaran/antrian', [
            'title'       => 'Antrian Pasien',
            'dataAntrian' => $dataAntrian
        ]);
    }
}
