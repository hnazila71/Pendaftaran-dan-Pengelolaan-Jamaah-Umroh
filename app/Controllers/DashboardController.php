<?php

namespace App\Controllers;

use App\Models\JamaahModel;
use App\Models\ProgramModel;
use App\Models\TransaksiModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    protected $jamaahModel;
    protected $programModel;
    protected $transaksiModel;

    public function __construct()
    {
        helper('NumberHelper'); // Pastikan helper dimuat

        $this->jamaahModel = new JamaahModel();
        $this->programModel = new ProgramModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $data['programs'] = $this->programModel->findAll();
        return view('dashboard', $data);
    }

    public function viewProgramTransactions($id_program)
    {
        $program = $this->programModel->find($id_program);
        if (!$program) {
            return redirect()->to('/dashboard')->with('error', 'Program tidak ditemukan.');
        }

        $transaksi = $this->transaksiModel
            ->select('transaksi.*, jamaah.nama_jamaah')
            ->where('id_program', $id_program)
            ->join('jamaah', 'transaksi.id_jamaah = jamaah.id')
            ->findAll();

        $data = [
            'program' => $program,
            'transaksi' => $transaksi
        ];

        return view('program_transactions', $data);
    }

    public function editDP1($id)
    {
        return $this->editDP($id, 'dp1');
    }

    public function editDP2($id)
    {
        return $this->editDP($id, 'dp2');
    }

    public function editDP3($id)
    {
        return $this->editDP($id, 'dp3');
    }

    // Fungsi umum untuk menampilkan form edit DP
    private function editDP($id, $dpField)
    {
        $transaksi = $this->transaksiModel
            ->select('transaksi.*, jamaah.nama_jamaah')
            ->join('jamaah', 'transaksi.id_jamaah = jamaah.id')
            ->where('transaksi.id', $id)
            ->first();

        if (!$transaksi) {
            return redirect()->to('/dashboard')->with('error', 'Transaksi tidak ditemukan.');
        }

        $data = [
            'transaksi' => $transaksi,
            'dpField' => $dpField,
            'fieldLabel' => strtoupper($dpField) // Label seperti DP1, DP2, atau DP3
        ];

        return view('edit_dp', $data);
    }

    // Fungsi untuk memperbarui nilai DP dan waktu edit
    public function updateDP($id)
    {
        $dpField = $this->request->getPost('dpField');
        $dpValue = str_replace(',', '', $this->request->getPost('dpValue')); // Hilangkan koma sebelum menyimpan

        // Ambil data transaksi berdasarkan ID
        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Tentukan nama kolom waktu edit untuk DP yang diubah
        $timeEditField = $dpField . '_time_edit';

        // Siapkan data untuk update
        $updateData = [
            $dpField => $dpValue,
            $timeEditField => date('Y-m-d H:i:s')
        ];

        $this->transaksiModel->update($id, $updateData);
        return redirect()->to('/dashboard/program/' . $transaksi['id_program'])
            ->with('success', ucfirst($dpField) . ' berhasil diperbarui.');
    }
}
