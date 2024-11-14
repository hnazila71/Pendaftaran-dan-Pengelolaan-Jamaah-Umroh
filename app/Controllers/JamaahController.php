<?php

namespace App\Controllers;

use App\Models\JamaahModel;
use CodeIgniter\Controller;

class JamaahController extends Controller
{
    public function addJamaah()
    {
        return view('add_jamaah'); // Menampilkan form tambah jamaah
    }

    public function saveJamaah()
    {
        // Validasi input
        if (!$this->validate([
            'nama_jamaah' => 'required|min_length[3]|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil model jamaah
        $jamaahModel = new JamaahModel();

        // Simpan data ke database
        $jamaahModel->save([
            'nama_jamaah' => $this->request->getPost('nama_jamaah')
        ]);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->to('/dashboard')->with('success', 'Jamaah berhasil ditambahkan!');
    }
}
