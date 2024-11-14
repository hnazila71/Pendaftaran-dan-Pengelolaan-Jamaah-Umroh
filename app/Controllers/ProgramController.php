<?php

namespace App\Controllers;

use App\Models\ProgramModel;
use CodeIgniter\Controller;

class ProgramController extends Controller
{
    public function addProgram()
    {
        return view('add_program'); // Menampilkan form tambah program
    }

    public function saveProgram()
    {
        // Validasi input
        if (!$this->validate([
            'nama_program' => 'required|min_length[3]|max_length[255]',
            'tanggal_program' => 'required|valid_date',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil model program
        $programModel = new ProgramModel();

        // Simpan data ke database
        $programModel->save([
            'nama_program' => $this->request->getPost('nama_program'),
            'tanggal_program' => $this->request->getPost('tanggal_program')
        ]);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->to('/dashboard')->with('success', 'Program berhasil ditambahkan!');
    }
}
