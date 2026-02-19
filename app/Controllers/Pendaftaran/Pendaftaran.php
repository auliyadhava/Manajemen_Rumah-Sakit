<?php

namespace App\Controllers\Pendaftaran;

use App\Controllers\BaseController;
use App\Models\Registration\AppointmentModel;
use App\Models\Registration\QueueModel;

class Pendaftaran extends BaseController
{
    protected $appointmentModel;
    protected $queueModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->queueModel       = new QueueModel();
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

        $totalPendaftaran = $this->appointmentModel
            ->where('schedule_date', $today)
            ->countAllResults();

        $totalWaiting = $this->appointmentModel
            ->where('status', 'waiting')
            ->where('schedule_date', $today)
            ->countAllResults();

        $totalQueue = $this->queueModel
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->where('appointments.schedule_date', $today)
            ->countAllResults();

        /* ======================
           5 PENDAFTARAN TERBARU
        ====================== */

        $pendaftaranTerbaru = $this->appointmentModel
            ->select('
                appointments.status,
                users.full_name,
                departments.name AS department_name
            ')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('departments', 'departments.department_id = appointments.department_id')
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
                users.full_name,
                departments.name AS department_name
            ')
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('departments', 'departments.department_id = appointments.department_id')
            ->where('appointments.schedule_date', $today)
            ->orderBy('queues.queue_number', 'DESC')
            ->limit(5)
            ->findAll();

        return view('pendaftaran/dashboard', [
            'title'               => 'Dashboard Pendaftaran',
            'totalPendaftaran'    => $totalPendaftaran,
            'totalWaiting'        => $totalWaiting,
            'totalQueue'          => $totalQueue,
            'pendaftaranTerbaru'  => $pendaftaranTerbaru,
            'antrianTerbaru'      => $antrianTerbaru,
        ]);
    }

    /* =======================
       LIST PENDAFTARAN PASIEN
       ======================= */
    public function pasien()
    {
        $dataPasien = $this->appointmentModel
            ->select('
                appointments.appointment_id,
                appointments.status,
                appointments.schedule_date,
                users.full_name,
                departments.name AS department_name
            ')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('departments', 'departments.department_id = appointments.department_id')
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
        // Ambil data appointment
        $appointment = $this->appointmentModel
            ->select('
                appointments.appointment_id,
                appointments.status,
                appointments.schedule_date,
                users.full_name,
                departments.name AS department_name
            ')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('departments', 'departments.department_id = appointments.department_id')
            ->where('appointments.appointment_id', $appointment_id)
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Cegah konfirmasi ulang
        if ($appointment['status'] === 'confirmed') {
            return redirect()->back()->with('warning', 'Pasien sudah dikonfirmasi');
        }

        /* =======================
           UPDATE STATUS APPOINTMENT
           ======================= */
        $this->appointmentModel->update($appointment_id, [
            'status' => 'confirmed'
        ]);

        /* =======================
           HITUNG NOMOR ANTRIAN
           ======================= */
        $today = date('Y-m-d');

        $lastQueue = $this->queueModel
            ->select('queues.queue_number')
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->where('appointments.schedule_date', $today)
            ->orderBy('queues.queue_number', 'DESC')
            ->first();

        $queueNumber = $lastQueue ? ((int)$lastQueue['queue_number'] + 1) : 1;

        /* =======================
           INSERT KE TABEL QUEUES
           ======================= */
        $this->queueModel->insert([
            'appointment_id' => $appointment_id,
            'queue_number'   => $queueNumber,
            'status'         => 'waiting'
        ]);

        /* =======================
           SIMPAN TIKET KE SESSION
           ======================= */
        session()->setFlashdata('queue_ticket', [
            'queue_number'  => str_pad($queueNumber, 3, '0', STR_PAD_LEFT),
            'full_name'     => $appointment['full_name'],
            'department'    => $appointment['department_name'],
            'schedule_date' => $appointment['schedule_date']
        ]);

        return redirect()->to('/pendaftaran/pasien')
            ->with('success', 'Pasien berhasil dikonfirmasi & masuk antrian');
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
                users.full_name,
                departments.name AS department_name
            ')
            ->join('appointments', 'appointments.appointment_id = queues.appointment_id')
            ->join('patients', 'patients.patient_id = appointments.patient_id')
            ->join('users', 'users.user_id = patients.user_id')
            ->join('departments', 'departments.department_id = appointments.department_id')
            ->where('appointments.schedule_date', $today)
            ->orderBy('queues.queue_number', 'ASC')
            ->findAll();

        return view('pendaftaran/antrian', [
            'title'       => 'Antrian Pasien',
            'dataAntrian' => $dataAntrian
        ]);
    }

    public function panggil()
    {
        $queueId = $this->request->getPost('queue_id');

        $queue = $this->queueModel
            ->where('queue_id', $queueId)
            ->first();

        if (!$queue) {
            return $this->response->setJSON(['status' => 'error']);
        }

        // Hanya update jika masih waiting
        if ($queue['status'] === 'waiting') {
            $this->queueModel
                ->where('queue_id', $queueId)
                ->set(['status' => 'called'])
                ->update();
        }

        return $this->response->setJSON(['status' => 'success']);
    }
}
