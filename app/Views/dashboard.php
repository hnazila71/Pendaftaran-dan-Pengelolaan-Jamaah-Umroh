<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Razek Pekajangan</title>
    <link rel="icon" type="image/png" href="/razek.png?v=3">
    <link rel="shortcut icon" href="/favicon.ico?v=3">
    <link rel="apple-touch-icon" href="/razek.png?v=3">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <?php
    $isAdmin = (bool) ($is_admin ?? false);
    $isSuperAdmin = (bool) ($is_super_admin ?? false);
    $adminRole = strtoupper((string) ($admin_role ?? 'viewer'));
    $adminName = (string) ($admin_nama ?? session()->get('admin_nama') ?? '');
    $roleLabel = $isSuperAdmin ? 'SUPER ADMIN' : $adminRole;
    ?>
    <main class="page-shell fade-in">
        <header class="topbar">
            <div>
                <span class="brand-chip"><span class="brand-dot"></span>Razek Pekajangan</span>
                <h1>Dashboard Jamaah Umroh</h1>
                <p>Halo <?= esc($adminName) ?>. Role kamu: <strong><?= esc($roleLabel) ?></strong>.</p>
            </div>
            <a href="<?= site_url('logout') ?>" class="btn btn-danger">Logout</a>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (! empty($db_issue)): ?>
            <div class="flash error"><?= esc($db_issue) ?></div>
        <?php endif; ?>

        <section class="actions-grid fade-in-slow">
            <a href="<?= site_url('keuangan') ?>" class="btn btn-secondary">Lihat Keuangan</a>
            <?php if ($isAdmin): ?>
                <a href="<?= site_url('dashboard/add-jamaah') ?>" class="btn btn-primary">Tambah Jamaah</a>
                <a href="<?= site_url('dashboard/add-program') ?>" class="btn btn-accent">Tambah Program</a>
                <a href="<?= site_url('dashboard/add-transaksi') ?>" class="btn btn-primary">Tambah Transaksi</a>
                <?php if ($isSuperAdmin): ?>
                    <a href="<?= site_url('dashboard/users') ?>" class="btn btn-accent">Persetujuan User</a>
                <?php endif; ?>
            <?php endif; ?>
        </section>

        <?php if (! $isAdmin): ?>
            <div class="flash" style="background: rgba(22, 102, 120, 0.08); border-color: rgba(22, 102, 120, 0.22); color: #1e4151;">
                Akun viewer bersifat baca-saja. Tambah/edit data hanya bisa dilakukan oleh admin.
            </div>
        <?php endif; ?>

        <section class="panel fade-in-slow">
            <div class="stack">
                <h2>Daftar Program</h2>
                <p>Harga modal dan harga jual ditentukan di program, lalu transaksi jamaah otomatis mengikuti nilai ini.</p>

                <?php if (! empty($programs)): ?>
                    <div class="program-grid">
                        <?php foreach ($programs as $program): ?>
                            <article class="program-card">
                                <h3><?= esc($program['nama_program']) ?></h3>
                                <p class="program-date">Tanggal: <?= esc($program['tanggal_program']) ?></p>
                                <p><strong>Harga Jual:</strong> Rp <?= number_format((float) ($program['harga_jual'] ?? 0), 0, ',', '.') ?></p>
                                <p><strong>Harga Modal:</strong> Rp <?= number_format((float) ($program['harga_modal'] ?? 0), 0, ',', '.') ?></p>
                                <a class="btn btn-primary" style="margin-top: 10px;" href="<?= site_url('dashboard/program/' . $program['id']) ?>">Lihat Detail</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Belum ada program. Tambahkan program pertama untuk mulai mencatat transaksi jamaah.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>

</html>
