<?php

namespace App\Controllers;

use App\Models\JamaahModel;
use App\Models\ProgramModel;
use App\Models\TransaksiModel;
use CodeIgniter\Controller;

class TransaksiController extends Controller
{
    public function addTransaksi()
    {
        $jamaahModel = new JamaahModel();
        $programModel = new ProgramModel();

        $data['jamaah'] = $jamaahModel->findAll(); // Ambil semua jamaah
        $data['programs'] = $programModel->findAll(); // Ambil semua program

        return view('add_transaksi', $data); // Menampilkan form tambah transaksi
    }

    public function saveTransaksi()
    {
        // Validasi input
        if (!$this->validate([
            'id_jamaah' => 'required',
            'id_program' => 'required',
            'harga' => 'required|decimal',
            'dp1' => 'required|decimal',
            'dp2' => 'required|decimal',
            'dp3' => 'required|decimal',
            'harga_modal' => 'required|decimal' // Validasi harga_modal
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari input form
        $harga = $this->request->getPost('harga');
        $dp1 = $this->request->getPost('dp1');
        $dp2 = $this->request->getPost('dp2');
        $dp3 = $this->request->getPost('dp3');
        $hargaModal = $this->request->getPost('harga_modal'); // Ambil harga_modal

        // Hitung kekurangan
        $kekurangan = $harga - $dp1 - $dp2 - $dp3;

        // Simpan data ke database
        $transaksiModel = new TransaksiModel();
        $transaksiModel->save([
            'id_jamaah' => $this->request->getPost('id_jamaah'),
            'id_program' => $this->request->getPost('id_program'),
            'harga' => $harga,
            'dp1' => $dp1,
            'dp2' => $dp2,
            'dp3' => $dp3,
            'kekurangan' => $kekurangan,
            'harga_modal' => $hargaModal // Simpan harga_modal
        ]);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->to('/dashboard')->with('success', 'Transaksi berhasil ditambahkan!');
    }
}
