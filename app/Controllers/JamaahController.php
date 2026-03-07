<?php

namespace App\Controllers;

use App\Models\JamaahModel;

class JamaahController extends AuthenticatedController
{
    public function addJamaah()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        return view('add_jamaah'); 
    }

    public function saveJamaah()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

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
