<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan</title>
    <link rel="icon" type="image/png" href="/razek.png?v=3">
    <link rel="shortcut icon" href="/favicon.ico?v=3">
    <link rel="apple-touch-icon" href="/razek.png?v=3">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <?php $isAdmin = (bool) ($is_admin ?? false); ?>
    <main class="page-shell fade-in">
        <header class="topbar">
            <div>
                <span class="brand-chip"><span class="brand-dot"></span>Keuangan</span>
                <h1>Arus Pengeluaran</h1>
                <p>Pantau total pengeluaran, status untung/rugi, dan log perubahan pengeluaran.</p>
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

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="flash error">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="metric-grid fade-in-slow">
            <?php
            $statusJualModal = $selisih_jual_modal >= 0 ? 'Untung' : 'Rugi';
            $statusSetelahPengeluaran = $selisih_setelah_pengeluaran >= 0 ? 'Untung' : 'Rugi';
            ?>
            <article class="metric-card">
                <p class="metric-label">Total Harga Jual</p>
                <p class="metric-value">Rp <?= number_format($total_harga_jual, 0, ',', '.') ?></p>
            </article>
            <article class="metric-card">
                <p class="metric-label">Total Harga Modal</p>
                <p class="metric-value">Rp <?= number_format($total_harga_modal, 0, ',', '.') ?></p>
            </article>
            <article class="metric-card">
                <p class="metric-label">Status Jual vs Modal</p>
                <p class="metric-value" style="color: <?= $selisih_jual_modal >= 0 ? '#1f6d44' : '#a03222' ?>;">
                    <?= $statusJualModal ?> Rp <?= number_format(abs($selisih_jual_modal), 0, ',', '.') ?>
                </p>
            </article>
            <article class="metric-card">
                <p class="metric-label">Total Pengeluaran</p>
                <p class="metric-value">Rp <?= number_format($total, 0, ',', '.') ?></p>
            </article>
            <article class="metric-card">
                <p class="metric-label">Status Setelah Pengeluaran</p>
                <p class="metric-value" style="color: <?= $selisih_setelah_pengeluaran >= 0 ? '#1f6d44' : '#a03222' ?>;">
                    <?= $statusSetelahPengeluaran ?> Rp <?= number_format(abs($selisih_setelah_pengeluaran), 0, ',', '.') ?>
                </p>
            </article>
        </section>

        <section class="table-shell fade-in-slow" style="margin-bottom: 14px;">
            <h2 style="margin-bottom: 12px;">Daftar Pengeluaran</h2>
            <table class="data-table" style="min-width: 740px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($pengeluaran)): ?>
                        <?php foreach ($pengeluaran as $item): ?>
                            <tr>
                                <td><?= esc($item['tanggal']) ?></td>
                                <td><?= esc($item['keterangan']) ?></td>
                                <td class="numeric">Rp <?= number_format((float) $item['jumlah'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($isAdmin): ?>
                                        <a href="<?= site_url('keuangan/edit/' . $item['id']) ?>" class="btn btn-accent">Edit</a>
                                    <?php else: ?>
                                        <span class="text-muted">Read-only</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Belum ada data pengeluaran.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <?php if ($isAdmin): ?>
            <section class="form-shell narrow fade-in-slow" style="margin-bottom: 14px;">
                <div class="stack">
                    <h2>Tambah Pengeluaran</h2>

                    <form action="<?= site_url('keuangan/save') ?>" method="post" class="form-grid" onsubmit="cleanRupiahInput(this)">
                        <?= csrf_field() ?>

                        <div class="field">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" required placeholder="Contoh: Biaya transport bandara" value="<?= esc(old('keterangan')) ?>">
                        </div>

                        <div class="field">
                            <label for="jumlah">Jumlah</label>
                            <input class="money" type="text" name="jumlah" id="jumlah" required inputmode="numeric" placeholder="Contoh: 5.000.000" value="<?= esc(old('jumlah')) ?>" oninput="formatRupiahInput(this)">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
                    </form>
                </div>
            </section>
        <?php else: ?>
            <div class="flash" style="background: rgba(22, 102, 120, 0.08); border-color: rgba(22, 102, 120, 0.22); color: #1e4151;">
                Akun viewer hanya bisa melihat data keuangan. Penambahan dan edit pengeluaran khusus admin.
            </div>
        <?php endif; ?>

        <section class="table-shell fade-in-slow">
            <h2 style="margin-bottom: 12px;">Log Edit Pengeluaran</h2>
            <table class="data-table" style="min-width: 980px;">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Editor</th>
                        <th>Aksi</th>
                        <th>Data Pengeluaran</th>
                        <th>Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($pengeluaran_logs)): ?>
                        <?php foreach ($pengeluaran_logs as $log): ?>
                            <tr>
                                <td>
                                    <?php if (! empty($log['edited_at'])): ?>
                                        <?= esc(date('d M Y H:i', strtotime((string) $log['edited_at']))) ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($log['edited_by'] ?? 'unknown') ?></td>
                                <td><?= esc(strtoupper((string) ($log['action'] ?? 'update'))) ?></td>
                                <td>
                                    ID #<?= esc((string) $log['pengeluaran_id']) ?><br>
                                    <?= esc((string) ($log['pengeluaran_keterangan'] ?? '-')) ?>
                                </td>
                                <td>
                                    <?php if (($log['action'] ?? '') === 'create'): ?>
                                        Data dibuat: <?= esc((string) ($log['new_tanggal'] ?? '-')) ?>, Rp <?= number_format((float) ($log['new_jumlah'] ?? 0), 0, ',', '.') ?>
                                    <?php else: ?>
                                        Tanggal: <?= esc((string) ($log['old_tanggal'] ?? '-')) ?> -> <?= esc((string) ($log['new_tanggal'] ?? '-')) ?><br>
                                        Keterangan: <?= esc((string) ($log['old_keterangan'] ?? '-')) ?> -> <?= esc((string) ($log['new_keterangan'] ?? '-')) ?><br>
                                        Jumlah: Rp <?= number_format((float) ($log['old_jumlah'] ?? 0), 0, ',', '.') ?> -> Rp <?= number_format((float) ($log['new_jumlah'] ?? 0), 0, ',', '.') ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Belum ada log edit pengeluaran.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script>
        function formatRupiahInput(input) {
            const digits = input.value.replace(/[^\d]/g, '');
            input.value = digits ? Number(digits).toLocaleString('id-ID') : '';
        }

        function cleanRupiahInput(form) {
            const field = form.querySelector('input[name="jumlah"]');
            if (field) {
                field.value = field.value.replace(/\./g, '');
            }
        }
    </script>
</body>

</html>
