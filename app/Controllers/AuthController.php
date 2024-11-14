<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        return view('login'); // Load the login view
    }

    public function loginProcess()
    {
        $model = new AdminModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $model->where('nama', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set('logged_in', true);
            session()->set('user_id', $user['id']);
            session()->set('user_name', $user['nama']);
            return redirect()->to('/dashboard');
        } else {
            session()->setFlashdata('msg', 'Invalid username or password.');
            return redirect()->to('/login');
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }


    public function register()
    {
        return view('register'); // Load register.php directly from app/Views/
    }

    public function registerProcess()
    {
        $model = new AdminModel();
        $rules = [
            'nama'     => 'required|min_length[3]|is_unique[admins.nama]',
            'password' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'nama'     => $this->request->getPost('nama'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        session()->setFlashdata('success', 'Registration successful. Please login.');
        return redirect()->to('/login');
    }
}
