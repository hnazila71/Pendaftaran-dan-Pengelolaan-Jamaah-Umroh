<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Razek Pekajangan</title>
    <link rel="icon" type="image/png" href="/razek.png?v=3">
    <link rel="shortcut icon" href="/favicon.ico?v=3">
    <link rel="apple-touch-icon" href="/razek.png?v=3">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="auth-shell fade-in">
        <section class="auth-card">
            <span class="brand-chip"><span class="brand-dot"></span>Razek Pekajangan</span>
            <h1 class="page-title">Masuk Admin</h1>
            <p class="page-subtitle">Gunakan akun admin untuk mengelola jamaah umroh dan transaksi pembayaran.</p>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('msg')): ?>
                <div class="flash error"><?= esc(session()->getFlashdata('msg')) ?></div>
            <?php endif; ?>

            <form action="<?= base_url('/login') ?>" method="post" class="form-grid">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required value="<?= esc(old('username')) ?>" placeholder="Masukkan username">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password">
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <div class="btn-row" style="margin-top: 10px;">
                <a class="btn btn-secondary" href="<?= site_url('login/google') ?>" style="width: 100%;">Login with Google</a>
            </div>

            <p style="margin-top: 14px;">Belum punya akun? <a href="<?= base_url('/register') ?>">Register di sini</a></p>
        </section>
    </main>
</body>

</html>
