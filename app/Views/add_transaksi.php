<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/ui.css') ?>">
</head>

<body>
    <main class="page-shell fade-in">
        <section class="form-shell">
            <div class="stack">
                <span class="brand-chip"><span class="brand-dot"></span>Transaksi Jamaah</span>
                <h1>Tambah Transaksi Program</h1>
                <p>Pilih jamaah dan program. Harga otomatis ikut dari program, pembayaran bisa dicicil dinamis kapan saja.</p>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="flash error">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('dashboard/add-transaksi') ?>" method="post" class="form-grid" onsubmit="removeCommas()">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="id_jamaah">Pilih Jamaah</label>
                        <select id="id_jamaah" name="id_jamaah" required>
                            <?php foreach ($jamaah as $j): ?>
                                <option value="<?= $j['id'] ?>" <?= old('id_jamaah') == $j['id'] ? 'selected' : '' ?>><?= esc($j['nama_jamaah']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="id_program">Pilih Program</label>
                        <select id="id_program" name="id_program" required onchange="updateProgramPriceInfo()">
                            <?php foreach ($programs as $program): ?>
                                <option
                                    value="<?= $program['id'] ?>"
                                    data-harga-jual="<?= esc((string) ($program['harga_jual'] ?? 0)) ?>"
                                    data-harga-modal="<?= esc((string) ($program['harga_modal'] ?? 0)) ?>"
                                    <?= old('id_program') == $program['id'] ? 'selected' : '' ?>
                                >
                                    <?= esc($program['nama_program']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="panel" style="padding: 14px;">
                        <p><strong>Harga Jual Program:</strong> <span id="hargaJualInfo">Rp 0</span></p>
                        <p><strong>Harga Modal Program:</strong> <span id="hargaModalInfo">Rp 0</span></p>
                    </div>

                    <div class="field">
                        <label for="pembayaran_awal">Pembayaran Awal (opsional)</label>
                        <input class="money" type="text" id="pembayaran_awal" name="pembayaran_awal" value="<?= esc(old('pembayaran_awal') ?: '0') ?>" oninput="formatNumber(this)">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
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
            const field = document.getElementById('pembayaran_awal');
            field.value = field.value.replace(/,/g, '');
        }

        function rupiah(value) {
            const numeric = Number(value || 0);
            return 'Rp ' + numeric.toLocaleString('id-ID');
        }

        function updateProgramPriceInfo() {
            const select = document.getElementById('id_program');
            const selected = select.options[select.selectedIndex];

            const hargaJual = selected.getAttribute('data-harga-jual') || 0;
            const hargaModal = selected.getAttribute('data-harga-modal') || 0;

            document.getElementById('hargaJualInfo').textContent = rupiah(hargaJual);
            document.getElementById('hargaModalInfo').textContent = rupiah(hargaModal);
        }

        updateProgramPriceInfo();
    </script>
</body>

</html>
