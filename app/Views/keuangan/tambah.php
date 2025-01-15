<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran</title>
    <style>
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            max-width: 300px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h1>Tambah Pengeluaran</h1>
    
    <form action="/keuangan/simpan" method="post">
        <?= csrf_field() ?>

        <!-- Tanggal -->
        <label for="tanggal">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" value="<?= old('tanggal') ?>" required>
        <div class="error"><?= isset($errors['tanggal']) ? $errors['tanggal'] : '' ?></div>

        <!-- Keterangan -->
        <label for="keterangan">Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" value="<?= old('keterangan') ?>" required>
        <div class="error"><?= isset($errors['keterangan']) ? $errors['keterangan'] : '' ?></div>

        <!-- Jumlah -->
        <label for="jumlah">Jumlah</label>
        <input type="number" name="jumlah" id="jumlah" value="<?= old('jumlah') ?>" required>
        <div class="error"><?= isset($errors['jumlah']) ? $errors['jumlah'] : '' ?></div>

        <!-- Submit Button -->
        <button type="submit">Simpan Pengeluaran</button>
    </form>

    <a href="/keuangan">
        <button>Kembali</button>
    </a>
</body>
</html>
