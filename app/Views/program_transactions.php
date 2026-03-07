<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Program dan Transaksi</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <?php $isAdmin = (bool) ($is_admin ?? false); ?>
    <main class="page-shell fade-in">
        <header class="topbar">
            <div>
                <span class="brand-chip"><span class="brand-dot"></span>Detail Program</span>
                <h1><?= esc($program['nama_program']) ?></h1>
                <p>Tanggal Program: <?= esc($program['tanggal_program']) ?></p>
                <p>Harga Jual: <strong>Rp <?= number_format((float) ($program['harga_jual'] ?? 0), 0, ',', '.') ?></strong> | Harga Modal: <strong>Rp <?= number_format((float) ($program['harga_modal'] ?? 0), 0, ',', '.') ?></strong></p>
            </div>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <section class="table-shell fade-in-slow">
            <h2 style="margin-bottom: 12px;">Daftar Transaksi Program</h2>

            <?php if (! empty($transaksi)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Jamaah</th>
                            <th>Harga Jual</th>
                            <th>Harga Modal</th>
                            <th>Total Bayar</th>
                            <th>Sisa Tagihan</th>
                            <th>Riwayat Pembayaran</th>
                            <th><?= $isAdmin ? 'Tambah Pembayaran' : 'Akses' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalSisa = 0;
                        foreach ($transaksi as $t):
                            $totalSisa += (float) ($t['sisa_tagihan'] ?? 0);
                        ?>
                            <tr>
                                <td><?= esc($t['nama_jamaah']) ?></td>
                                <td class="numeric">Rp <?= number_format((float) $t['harga'], 0, ',', '.') ?></td>
                                <td class="numeric">Rp <?= number_format((float) $t['harga_modal'], 0, ',', '.') ?></td>
                                <td class="numeric">Rp <?= number_format((float) ($t['total_bayar'] ?? 0), 0, ',', '.') ?></td>
                                <td class="numeric">Rp <?= number_format((float) ($t['sisa_tagihan'] ?? 0), 0, ',', '.') ?></td>
                                <td>
                                    <?php if (! empty($t['riwayat_pembayaran'])): ?>
                                        <div class="payment-history">
                                            <?php foreach ($t['riwayat_pembayaran'] as $history): ?>
                                                <div class="payment-item">
                                                    <div class="payment-amount">Rp <?= number_format((float) ($history['nominal'] ?? 0), 0, ',', '.') ?></div>
                                                    <div class="payment-meta">
                                                        <?php if (! empty($history['dibayar_pada'])): ?>
                                                            <?= esc(date('d M Y H:i', strtotime((string) $history['dibayar_pada']))) ?>
                                                        <?php else: ?>
                                                            Waktu tidak tersedia
                                                        <?php endif; ?>
                                                        <?php if (! empty($history['keterangan'])): ?>
                                                            - <?= esc((string) $history['keterangan']) ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="payment-empty">Belum ada pembayaran</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($isAdmin): ?>
                                        <form action="<?= site_url('dashboard/transaksi/' . $t['id'] . '/pembayaran') ?>" method="post" class="form-grid" onsubmit="removeCommas(this)">
                                            <?= csrf_field() ?>
                                            <input class="money" type="text" name="nominal" value="0" oninput="formatNumber(this)" required>
                                            <input type="text" name="keterangan" placeholder="Catatan (opsional)">
                                            <button type="submit" class="btn btn-accent">Simpan Bayar</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Viewer tidak bisa menambah pembayaran.</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="table-note">Total Sisa Tagihan Program: Rp <?= number_format($totalSisa, 0, ',', '.') ?></p>
            <?php else: ?>
                <p>Belum ada transaksi untuk program ini.</p>
            <?php endif; ?>
        </section>
    </main>

    <script>
        function formatNumber(input) {
            let value = input.value.replace(/,/g, '').trim();

            if (value === '') {
                input.value = '';
                return;
            }

            const numeric = Number(value);
            input.value = Number.isFinite(numeric) ? numeric.toLocaleString('en-US') : input.value;
        }

        function removeCommas(form) {
            const nominal = form.querySelector('input[name="nominal"]');
            nominal.value = nominal.value.replace(/,/g, '');
        }
    </script>
</body>

</html>
