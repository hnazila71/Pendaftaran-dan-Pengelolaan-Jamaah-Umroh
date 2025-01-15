<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PengeluaranModel;

class KeuanganController extends Controller
{
    public function index()
    {
        $pengeluaranModel = new PengeluaranModel();
        $data['pengeluaran'] = $pengeluaranModel->findAll(); // Ambil semua data pengeluaran

        // Hitung total pengeluaran
        $total = 0;
        foreach ($data['pengeluaran'] as $item) {
            $total += $item['jumlah'];
        }
        $data['total'] = $total; // Simpan total pengeluaran

        return view('keuangan/index', $data);
    }

    public function save()
    {
        $pengeluaranModel = new PengeluaranModel();

        // Validasi input
        if ($this->validate([
            'keterangan' => 'required',
            'jumlah' => 'required|numeric',
        ])) {
            // Ambil tanggal hari ini secara otomatis
            $tanggal = date('Y-m-d');

            // Simpan data pengeluaran
            $data = [
                'tanggal' => $tanggal,
                'keterangan' => $this->request->getPost('keterangan'),
                'jumlah' => $this->request->getPost('jumlah'),
            ];
            $pengeluaranModel->save($data);

            // Redirect ke halaman pengeluaran setelah disimpan
            return redirect()->to('/keuangan');
        } else {
            // Jika validasi gagal, kembali ke form tambah dengan pesan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }
}
