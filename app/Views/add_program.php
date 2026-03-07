<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Program</title>
    <link rel="icon" type="image/png" href="/razek.png?v=2">
    <link rel="shortcut icon" href="/razek.png?v=2">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="page-shell fade-in">
        <section class="form-shell narrow">
            <div class="stack">
                <span class="brand-chip"><span class="brand-dot"></span>Program Umroh</span>
                <h1>Tambah Program Baru</h1>
                <p>Set harga jual dan harga modal di level program agar transaksi jamaah jadi konsisten.</p>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="flash error">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('dashboard/add-program') ?>" method="post" class="form-grid" onsubmit="removeCommas()">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="nama_program">Nama Program</label>
                        <input type="text" id="nama_program" name="nama_program" required value="<?= esc(old('nama_program')) ?>" placeholder="Contoh: Umroh Akhir Tahun">
                    </div>

                    <div class="field">
                        <label for="tanggal_program">Tanggal Program</label>
                        <input type="date" id="tanggal_program" name="tanggal_program" required value="<?= esc(old('tanggal_program')) ?>">
                    </div>

                    <div class="field">
                        <label for="harga_jual">Harga Jual per Jamaah</label>
                        <input class="money" type="text" id="harga_jual" name="harga_jual" required value="<?= esc(old('harga_jual') ?: '0') ?>" oninput="formatNumber(this)">
                    </div>

                    <div class="field">
                        <label for="harga_modal">Harga Modal per Jamaah</label>
                        <input class="money" type="text" id="harga_modal" name="harga_modal" required value="<?= esc(old('harga_modal') ?: '0') ?>" oninput="formatNumber(this)">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Program</button>
                </form>

                <div class="btn-row">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
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

        function removeCommas() {
            ['harga_jual', 'harga_modal'].forEach((id) => {
                const field = document.getElementById(id);
                field.value = field.value.replace(/,/g, '');
            });
        }
    </script>
</body>

</html>
