<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jamaah</title>
    <link rel="icon" type="image/png" href="/razek.png?v=3">
    <link rel="shortcut icon" href="/favicon.ico?v=3">
    <link rel="apple-touch-icon" href="/razek.png?v=3">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="page-shell fade-in">
        <section class="form-shell narrow">
            <div class="stack">
                <span class="brand-chip"><span class="brand-dot"></span>Data Jamaah</span>
                <h1>Tambah Jamaah Baru</h1>
                <p>Masukkan nama jamaah yang akan didaftarkan.</p>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="flash error">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('dashboard/add-jamaah') ?>" method="post" class="form-grid">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="nama_jamaah">Nama Jamaah</label>
                        <input type="text" id="nama_jamaah" name="nama_jamaah" required value="<?= esc(old('nama_jamaah')) ?>" placeholder="Contoh: Ahmad Fauzi">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Jamaah</button>
                </form>

                <div class="btn-row">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
