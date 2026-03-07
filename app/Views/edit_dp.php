<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= esc($fieldLabel) ?></title>
    <link rel="icon" type="image/png" href="/razek.png?v=2">
    <link rel="shortcut icon" href="/razek.png?v=2">
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="page-shell fade-in">
        <section class="form-shell narrow">
            <div class="stack">
                <span class="brand-chip"><span class="brand-dot"></span>Update Pembayaran</span>
                <h1>Edit <?= esc($fieldLabel) ?></h1>
                <p>Jamaah: <strong><?= esc($transaksi['nama_jamaah']) ?></strong></p>

                <form action="<?= site_url('dashboard/update-dp/' . $transaksi['id']) ?>" method="post" class="form-grid" onsubmit="removeCommas()">
                    <?= csrf_field() ?>
                    <input type="hidden" name="dpField" value="<?= esc($dpField) ?>">

                    <div class="field">
                        <label for="dpValue"><?= esc($fieldLabel) ?></label>
                        <?php $formattedValue = number_format((float) $transaksi[$dpField], 0, '.', ','); ?>
                        <input class="money" type="text" id="dpValue" name="dpValue" value="<?= esc($formattedValue) ?>" required oninput="formatNumber(this)">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>

                <div class="btn-row">
                    <a href="<?= site_url('dashboard/program/' . $transaksi['id_program']) ?>" class="btn btn-secondary">Kembali ke Detail Program</a>
                </div>
            </div>
        </section>
    </main>

    <script>
        function formatNumber(input) {
            const clean = input.value.replace(/,/g, '').trim();
            if (clean === '') {
                input.value = '';
                return;
            }

            const numeric = Number(clean);
            input.value = Number.isFinite(numeric) ? numeric.toLocaleString('en-US') : input.value;
        }

        function removeCommas() {
            const dpValue = document.getElementById('dpValue');
            dpValue.value = dpValue.value.replace(/,/g, '');
        }
    </script>
</body>

</html>
