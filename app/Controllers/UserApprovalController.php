<?php

namespace App\Controllers;

use App\Models\AdminModel;

class UserApprovalController extends AuthenticatedController
{
    private const ROLE_ADMIN = 'admin';
    private const ROLE_VIEWER = 'viewer';
    private const STATUS_APPROVED = 'approved';
    private const STATUS_REJECTED = 'rejected';

    public function index()
    {
        $redirect = $this->requireSuperAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $adminModel = new AdminModel();

        $pendingUsers = $adminModel
            ->select('admins.*, approver.nama as approver_nama')
            ->join('admins as approver', 'approver.id = admins.approved_by', 'left')
            ->where('admins.approval_status', 'pending')
            ->orderBy('admins.id', 'DESC')
            ->findAll();

        $allUsers = $adminModel
            ->select('admins.*, approver.nama as approver_nama')
            ->join('admins as approver', 'approver.id = admins.approved_by', 'left')
            ->orderBy('admins.id', 'DESC')
            ->findAll();

        return view('admin_users', [
            'pending_users' => $pendingUsers,
            'all_users' => $allUsers,
            'current_admin_id' => (int) session()->get('admin_id'),
            'is_super_admin' => true,
        ]);
    }

    public function approve(int $adminId)
    {
        $redirect = $this->requireSuperAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $targetRole = strtolower(trim((string) $this->request->getPost('role')));
        if (! in_array($targetRole, [self::ROLE_ADMIN, self::ROLE_VIEWER], true)) {
            return redirect()->to('/dashboard/users')->with('error', 'Role tidak valid.');
        }

        $adminModel = new AdminModel();
        $targetUser = $adminModel->find($adminId);

        if (! $targetUser) {
            return redirect()->to('/dashboard/users')->with('error', 'Akun tidak ditemukan.');
        }

        $currentAdminId = (int) session()->get('admin_id');
        $manageError = $this->manageGuard($targetUser, $currentAdminId);
        if ($manageError !== null) {
            return redirect()->to('/dashboard/users')->with('error', $manageError);
        }

        $adminModel->update($adminId, [
            'role' => $targetRole,
            'approval_status' => self::STATUS_APPROVED,
            'approved_by' => $currentAdminId,
            'approved_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/dashboard/users')->with(
            'success',
            'Akun ' . ($targetUser['nama'] ?? ('#' . $adminId)) . ' disetujui sebagai ' . strtoupper($targetRole) . '.'
        );
    }

    public function reject(int $adminId)
    {
        $redirect = $this->requireSuperAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $adminModel = new AdminModel();
        $targetUser = $adminModel->find($adminId);

        if (! $targetUser) {
            return redirect()->to('/dashboard/users')->with('error', 'Akun tidak ditemukan.');
        }

        $currentAdminId = (int) session()->get('admin_id');
        $manageError = $this->manageGuard($targetUser, $currentAdminId);
        if ($manageError !== null) {
            return redirect()->to('/dashboard/users')->with('error', $manageError);
        }

        $adminModel->update($adminId, [
            'role' => self::ROLE_VIEWER,
            'approval_status' => self::STATUS_REJECTED,
            'approved_by' => $currentAdminId,
            'approved_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/dashboard/users')->with(
            'success',
            'Akun ' . ($targetUser['nama'] ?? ('#' . $adminId)) . ' berhasil ditolak.'
        );
    }

    public function updateRole(int $adminId)
    {
        $redirect = $this->requireSuperAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $targetRole = strtolower(trim((string) $this->request->getPost('role')));
        if (! in_array($targetRole, [self::ROLE_ADMIN, self::ROLE_VIEWER], true)) {
            return redirect()->to('/dashboard/users')->with('error', 'Role tidak valid.');
        }

        $adminModel = new AdminModel();
        $targetUser = $adminModel->find($adminId);

        if (! $targetUser) {
            return redirect()->to('/dashboard/users')->with('error', 'Akun tidak ditemukan.');
        }

        $currentAdminId = (int) session()->get('admin_id');
        $manageError = $this->manageGuard($targetUser, $currentAdminId);
        if ($manageError !== null) {
            return redirect()->to('/dashboard/users')->with('error', $manageError);
        }

        $status = strtolower((string) ($targetUser['approval_status'] ?? 'pending'));
        if ($status !== self::STATUS_APPROVED) {
            return redirect()->to('/dashboard/users')->with('error', 'Role hanya bisa diubah untuk akun yang sudah approved.');
        }

        $currentRole = strtolower((string) ($targetUser['role'] ?? self::ROLE_VIEWER));
        if ($currentRole === $targetRole) {
            return redirect()->to('/dashboard/users')->with('success', 'Role tidak berubah karena nilainya sama.');
        }

        $adminModel->update($adminId, [
            'role' => $targetRole,
            'approved_by' => $currentAdminId,
            'approved_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/dashboard/users')->with(
            'success',
            'Role akun ' . ($targetUser['nama'] ?? ('#' . $adminId)) . ' diubah ke ' . strtoupper($targetRole) . '.'
        );
    }

    private function manageGuard(array $targetUser, int $currentAdminId): ?string
    {
        $targetId = (int) ($targetUser['id'] ?? 0);
        if ($targetId === $currentAdminId) {
            return 'Tidak bisa mengubah role/approval akun sendiri.';
        }

        if ($this->toBool($targetUser['is_super_admin'] ?? false)) {
            return 'Role akun super admin tidak bisa diubah.';
        }

        return null;
    }

    private function toBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value === 1;
        }

        $text = strtolower(trim((string) $value));
        return in_array($text, ['1', 't', 'true', 'yes', 'y'], true);
    }
}
