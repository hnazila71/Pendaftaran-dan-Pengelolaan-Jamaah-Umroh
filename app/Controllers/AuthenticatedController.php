<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

abstract class AuthenticatedController extends Controller
{
    protected function requireLogin(): ?RedirectResponse
    {
        if ((bool) session()->get('isLoggedIn')) {
            return null;
        }

        return redirect()->to('/login')->with('msg', 'Silakan login terlebih dahulu.');
    }

    protected function requireAdmin(): ?RedirectResponse
    {
        $redirect = $this->requireLogin();
        if ($redirect instanceof RedirectResponse) {
            return $redirect;
        }

        if ((string) session()->get('admin_role') === 'admin') {
            return null;
        }

        return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Fitur ini hanya untuk admin.');
    }

    protected function requireSuperAdmin(): ?RedirectResponse
    {
        $redirect = $this->requireLogin();
        if ($redirect instanceof RedirectResponse) {
            return $redirect;
        }

        if ($this->isSuperAdmin()) {
            return null;
        }

        return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Fitur ini hanya untuk super admin.');
    }

    protected function isAdmin(): bool
    {
        return (bool) session()->get('isLoggedIn')
            && (string) session()->get('admin_role') === 'admin';
    }

    protected function isSuperAdmin(): bool
    {
        return $this->isAdmin() && (bool) session()->get('is_super_admin');
    }
}
