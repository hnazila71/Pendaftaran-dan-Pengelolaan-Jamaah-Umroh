<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Razek Pekajangan</title>
    <link rel="icon" type="image/png" href="/razek.png?v=3">
    <link rel="shortcut icon" href="/favicon.ico?v=3">
    <link rel="apple-touch-icon" href="/razek.png?v=3">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="auth-shell fade-in">
        <section class="auth-card">
            <span class="brand-chip"><span class="brand-dot"></span>Razek Pekajangan</span>
            <h1 class="page-title">Buat Akun Admin</h1>
            <p class="page-subtitle">Akun ini dipakai untuk akses dashboard pengelolaan jamaah dan pembayaran.</p>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="flash error">
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('register-process') ?>" method="post" class="form-grid">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="nama">Username</label>
                    <input type="text" id="nama" name="nama" required value="<?= esc(old('nama')) ?>" placeholder="Minimal 3 karakter">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
                </div>

                <button type="submit" class="btn btn-primary">Buat Akun</button>
            </form>

            <p style="margin-top: 14px;">Sudah punya akun? <a href="<?= base_url('/login') ?>">Kembali ke login</a></p>
        </section>
    </main>
</body>

</html>
