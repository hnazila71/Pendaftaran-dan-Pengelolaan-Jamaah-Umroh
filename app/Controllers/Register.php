<?php

namespace App\Controllers;

use App\Models\AdminModel; // Update this line to use AdminModel
use CodeIgniter\Controller;

class Register extends Controller
{
    public function index()
    {
        return view('register'); // Make sure this view exists in app/Views
    }

    public function save()
    {
        $model = new AdminModel(); // Change to AdminModel
        $nama = $this->request->getPost('nama');
        $password = $this->request->getPost('password');

        // Check if the name already exists
        $existingAdmin = $model->where('nama', $nama)->first();

        if ($existingAdmin) {
            // Name already exists, set a flash message and redirect back to the register form
            session()->setFlashdata('msg', 'Nama sudah ada.');
            return redirect()->to('/register')->withInput();
        }

        // Save the new admin
        $data = [
            'nama' => $nama,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $model->save($data);

        // Redirect to the login page after successful registration
        session()->setFlashdata('msg', 'Registrasi berhasil. Silakan login.');
        return redirect()->to('/login');
    }
}
