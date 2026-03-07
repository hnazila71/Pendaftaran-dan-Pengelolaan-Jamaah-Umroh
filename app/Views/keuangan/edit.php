<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengeluaran</title>
    <link rel="icon" type="image/png" href="/razek.png?v=3">
    <link rel="shortcut icon" href="/favicon.ico?v=3">
    <link rel="apple-touch-icon" href="/razek.png?v=3">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="page-shell fade-in">
        <section class="form-shell narrow">
            <div class="stack">
                <span class="brand-chip"><span class="brand-dot"></span>Edit Pengeluaran</span>
                <h1>Perbarui Data Pengeluaran</h1>
                <p>ID Pengeluaran: #<?= esc((string) $pengeluaran['id']) ?></p>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="flash error">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('keuangan/update/' . $pengeluaran['id']) ?>" method="post" class="form-grid" onsubmit="cleanRupiahInput(this)">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" required value="<?= esc(old('tanggal') ?: $pengeluaran['tanggal']) ?>">
                    </div>

                    <div class="field">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan" required value="<?= esc(old('keterangan') ?: $pengeluaran['keterangan']) ?>">
                    </div>

                    <div class="field">
                        <label for="jumlah">Jumlah</label>
                        <input class="money" type="text" id="jumlah" name="jumlah" required inputmode="numeric" value="<?= esc(old('jumlah') ?: number_format((float) $pengeluaran['jumlah'], 0, ',', '.')) ?>" oninput="formatRupiahInput(this)">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>

                <div class="btn-row">
                    <a href="<?= site_url('keuangan') ?>" class="btn btn-secondary">Kembali ke Keuangan</a>
                </div>
            </div>
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
