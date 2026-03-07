<?php

namespace App\Controllers;

use App\Models\ProgramModel;

class ProgramController extends AuthenticatedController
{
    public function addProgram()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        return view('add_program');
    }

    public function saveProgram()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $hargaJual = $this->parseMoney((string) $this->request->getPost('harga_jual'));
        $hargaModal = $this->parseMoney((string) $this->request->getPost('harga_modal'));

        if (! $this->validate([
            'nama_program' => 'required|min_length[3]|max_length[255]',
            'tanggal_program' => 'required|valid_date',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($hargaJual <= 0) {
            return redirect()->back()->withInput()->with('errors', ['Harga jual harus lebih dari 0.']);
        }

        if ($hargaModal < 0) {
            return redirect()->back()->withInput()->with('errors', ['Harga modal tidak boleh negatif.']);
        }

        $programModel = new ProgramModel();
        $programModel->save([
            'nama_program' => $this->request->getPost('nama_program'),
            'tanggal_program' => $this->request->getPost('tanggal_program'),
            'harga_modal' => $hargaModal,
            'harga_jual' => $hargaJual,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Program berhasil ditambahkan!');
    }

    private function parseMoney(string $value): float
    {
        $normalized = str_replace(',', '', trim($value));

        if ($normalized === '') {
            return 0;
        }

        return (float) $normalized;
    }
}
