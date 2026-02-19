<?php

namespace App\Controllers\Pasien;

use App\Controllers\BaseController;

class Pasien extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /* ===============================
     * HELPER: ambil patient_id dari session
     * =============================== */
    private function getPatientId()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return null;
        }

        $patient = $this->db->table('patients')
            ->where('user_id', $userId)
            ->get()
            ->getRow();

        return $patient ? $patient->patient_id : null;
    }

    /* ===============================
     * 1. DASHBOARD PASIEN
     * =============================== */
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/');
        }

        return view('Pasien/dashboard');
    }

    // Alias untuk index jika di routes dipanggil /dashboard
    public function dashboard()
    {
        return $this->index();
    }

    /* ===============================
     * 2. FORM BOOKING
     * =============================== */
    public function booking()
    {
        $patientId = $this->getPatientId();

        if (!$patientId) {
            return redirect()->to('/');
        }

        $data['departments'] = $this->db
            ->table('departments')
            ->get()
            ->getResult();

        return view('Pasien/booking', $data);
    }

    /* ===============================
     * 2b. AJAX: AMBIL DOKTER & JADWAL
     * =============================== */

    // Dipanggil saat milih Poliklinik
    public function getDoctorsByDept($deptId)
    {
        $doctors = $this->db->table('doctors')
            ->select('doctors.doctor_id, users.full_name') // Ambil full_name dari tabel users
            ->join('users', 'users.user_id = doctors.user_id') // Gabungkan tabel doctors dan users
            ->where('doctors.department_id', $deptId)
            ->get()
            ->getResult();

        return $this->response->setJSON($doctors);
    }

    // Dipanggil saat milih Dokter
    public function getSchedulesByDoctor($doctorId)
    {
        // Pastikan nama tabel kamu 'doctor_schedules'
        $schedules = $this->db->table('doctor_schedules')
            ->where('doctor_id', $doctorId)
            ->get()
            ->getResult();

        return $this->response->setJSON($schedules);
    }

    /* ===============================
     * 2c. SIMPAN BOOKING
     * =============================== */
    public function store()
    {
        $patientId = $this->getPatientId();

        if (!$patientId) {
            return redirect()->to('/');
        }

        $data = [
            'patient_id'    => $patientId,
            'schedule_date' => $this->request->getPost('schedule_date'),
            'department_id' => $this->request->getPost('department_id'),
            'doctor_id'     => $this->request->getPost('doctor_id'),
            'schedule_id'   => $this->request->getPost('schedule_id'),
            'status'        => 'waiting'
        ];

        // Validasi simpel
        if (empty($data['doctor_id']) || empty($data['schedule_id'])) {
            return redirect()->back()->with('error', 'Mohon pilih dokter dan jadwal terlebih dahulu.');
        }

        $this->db->table('appointments')->insert($data);

        return redirect()->to('/pasien/riwayat')->with('success', 'Booking berhasil dibuat!');
    }


    /* ===============================
     * 3. RIWAYAT APPOINTMENT
     * =============================== */
    public function riwayat()
    {
        $patientId = $this->getPatientId();

        if (!$patientId) {
            return redirect()->to('/');
        }

        $data['appointments'] = $this->db->query("
            SELECT 
                a.appointment_id,
                a.schedule_date,
                a.status,
                d.name AS department
            FROM appointments a
            JOIN departments d ON d.department_id = a.department_id
            WHERE a.patient_id = ?
            ORDER BY a.schedule_date DESC
        ", [$patientId])->getResult();

        return view('Pasien/riwayat', $data);
    }

    /* ===============================
     * 4. MONITOR ANTRIAN
     * =============================== */
    public function antrian()
    {
        $patientId = $this->getPatientId();

        if (!$patientId) {
            return redirect()->to('/');
        }

        $data['queues'] = $this->db->query("
            SELECT 
                q.queue_number,
                q.status,
                a.schedule_date,
                d.name AS department,
                a.appointment_id
            FROM queues q
            JOIN appointments a ON a.appointment_id = q.appointment_id
            JOIN departments d ON d.department_id = a.department_id
            WHERE a.patient_id = ?
              AND q.status != 'done'
            ORDER BY q.queue_id DESC
        ", [$patientId])->getResult();

        return view('Pasien/antrian', $data);
    }

    /* ===============================
     * 5. DETAIL PEMERIKSAAN
     * =============================== */
    public function detail_pemeriksaan($appointment_id)
    {
        $patientId = $this->getPatientId();

        if (!$patientId) {
            return redirect()->to('/');
        }

        // pemeriksaan
        $data['pemeriksaan'] = $this->db
            ->table('examinations')
            ->where('appointment_id', $appointment_id)
            ->get()
            ->getRow();

        // resep
        if ($data['pemeriksaan']) {
            $data['resep'] = $this->db->query("
                SELECT 
                    m.name,
                    pi.dosage,
                    pi.quantity,
                    pi.instructions
                FROM prescriptions p
                JOIN prescription_items pi ON p.prescription_id = pi.prescription_id
                JOIN medicines m ON pi.medicine_id = m.medicine_id
                WHERE p.exam_id = ?
            ", [$data['pemeriksaan']->exam_id])->getResult();
        } else {
            $data['resep'] = [];
        }

        // pembayaran
        $data['pembayaran'] = $this->db
            ->table('payments')
            ->where('appointment_id', $appointment_id)
            ->get()
            ->getRow();

        return view('Pasien/detail_pemeriksaan', $data);
    }
}
