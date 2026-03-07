<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Pengguna</title>
    <link rel="icon" type="image/png" href="/razek.png?v=2">
    <link rel="shortcut icon" href="/razek.png?v=2">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="page-shell fade-in">
        <header class="topbar">
            <div>
                <span class="brand-chip"><span class="brand-dot"></span>Manajemen Akses</span>
                <h1>Persetujuan Login Google</h1>
                <p>Super admin dapat menyetujui akun baru dan mengubah role admin/viewer.</p>
            </div>
            <div class="btn-row">
                <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Dashboard</a>
                <a href="<?= site_url('logout') ?>" class="btn btn-danger">Logout</a>
            </div>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <section class="table-shell fade-in-slow" style="margin-bottom: 14px;">
            <h2 style="margin-bottom: 12px;">Akun Menunggu Persetujuan</h2>
            <table class="data-table" style="min-width: 980px;">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Google ID</th>
                        <th>Status</th>
                        <th>Setujui</th>
                        <th>Tolak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($pending_users)): ?>
                        <?php foreach ($pending_users as $user): ?>
                            <tr>
                                <td><?= esc((string) ($user['nama'] ?? '-')) ?></td>
                                <td><?= esc((string) ($user['email'] ?? '-')) ?></td>
                                <td><span class="text-muted"><?= esc((string) ($user['google_id'] ?? '-')) ?></span></td>
                                <td><span class="badge badge-warning">Pending</span></td>
                                <td>
                                    <form method="post" action="<?= site_url('dashboard/users/' . $user['id'] . '/approve') ?>" class="inline-form">
                                        <?= csrf_field() ?>
                                        <select name="role" required>
                                            <option value="viewer">Viewer</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Setujui</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="<?= site_url('dashboard/users/' . $user['id'] . '/reject') ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Tidak ada akun pending.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="table-shell fade-in-slow">
            <h2 style="margin-bottom: 12px;">Daftar Semua Pengguna</h2>
            <table class="data-table" style="min-width: 1020px;">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Super Admin</th>
                        <th>Status</th>
                        <th>Disetujui Oleh</th>
                        <th>Waktu Persetujuan</th>
                        <th>Ubah Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($all_users)): ?>
                        <?php foreach ($all_users as $user): ?>
                            <?php
                            $role = strtolower((string) ($user['role'] ?? 'viewer'));
                            $status = strtolower((string) ($user['approval_status'] ?? 'pending'));
                            $isSuperAdminRow = in_array(
                                strtolower(trim((string) ($user['is_super_admin'] ?? '0'))),
                                ['1', 't', 'true', 'yes', 'y'],
                                true
                            );
                            ?>
                            <tr>
                                <td>
                                    <?= esc((string) ($user['nama'] ?? '-')) ?>
                                    <?php if ((int) ($user['id'] ?? 0) === (int) ($current_admin_id ?? 0)): ?>
                                        <span class="text-muted">(Kamu)</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc((string) ($user['email'] ?? '-')) ?></td>
                                <td>
                                    <span class="badge <?= $role === 'admin' ? 'badge-ok' : 'badge-neutral' ?>">
                                        <?= esc(strtoupper($role)) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $isSuperAdminRow ? 'badge-ok' : 'badge-neutral' ?>">
                                        <?= $isSuperAdminRow ? 'YA' : 'TIDAK' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?=
                                        $status === 'approved' ? 'badge-ok' :
                                        ($status === 'rejected' ? 'badge-danger' : 'badge-warning')
                                    ?>">
                                        <?= esc(strtoupper($status)) ?>
                                    </span>
                                </td>
                                <td><?= esc((string) ($user['approver_nama'] ?? '-')) ?></td>
                                <td>
                                    <?php if (! empty($user['approved_at'])): ?>
                                        <?= esc(date('d M Y H:i', strtotime((string) $user['approved_at']))) ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $isSelf = (int) ($user['id'] ?? 0) === (int) ($current_admin_id ?? 0);
                                    $canChangeRole = $status === 'approved' && ! $isSelf && ! $isSuperAdminRow;
                                    ?>
                                    <?php if ($canChangeRole): ?>
                                        <form method="post" action="<?= site_url('dashboard/users/' . $user['id'] . '/role') ?>" class="inline-form">
                                            <?= csrf_field() ?>
                                            <select name="role" required>
                                                <option value="viewer" <?= $role === 'viewer' ? 'selected' : '' ?>>Viewer</option>
                                                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    <?php elseif ($isSuperAdminRow): ?>
                                        <span class="text-muted">Role super admin tidak bisa diubah.</span>
                                    <?php elseif ($isSelf): ?>
                                        <span class="text-muted">Akun sendiri tidak bisa diubah.</span>
                                    <?php else: ?>
                                        <span class="text-muted">Hanya untuk akun approved.</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Belum ada data pengguna.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>
