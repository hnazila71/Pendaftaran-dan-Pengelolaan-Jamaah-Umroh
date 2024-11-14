<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 1.8em;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .error-message {
            color: #d9534f;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        select,
        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s ease;
            text-align: right;
        }

        select:focus,
        input[type="text"]:focus {
            border-color: #007BFF;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tambah Transaksi</h1>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="error-message">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('dashboard/add-transaksi') ?>" method="post" onsubmit="removeCommas()">
            <label for="id_jamaah">Pilih Jamaah:</label>
            <select id="id_jamaah" name="id_jamaah" required>
                <?php foreach ($jamaah as $j): ?>
                    <option value="<?= $j['id'] ?>"><?= esc($j['nama_jamaah']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="id_program">Pilih Program:</label>
            <select id="id_program" name="id_program" required>
                <?php foreach ($programs as $program): ?>
                    <option value="<?= $program['id'] ?>"><?= esc($program['nama_program']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="harga">Harga:</label>
            <input type="text" id="harga" name="harga" value="0" oninput="formatNumber(this)">

            <label for="dp1">DP 1:</label>
            <input type="text" id="dp1" name="dp1" value="0" oninput="formatNumber(this)">

            <label for="dp2">DP 2:</label>
            <input type="text" id="dp2" name="dp2" value="0" oninput="formatNumber(this)">

            <label for="dp3">DP 3:</label>
            <input type="text" id="dp3" name="dp3" value="0" oninput="formatNumber(this)">

            <button type="submit" class="submit-button">Tambah Transaksi</button>
        </form>
    </div>

    <script>
        function formatNumber(input) {
            // Menghapus koma yang ada
            let value = input.value.replace(/,/g, '');
            input.value = parseFloat(value || 0).toLocaleString('en-US');
        }

        function removeCommas() {
            // Menghapus koma dari input sebelum mengirimkan ke server
            document.getElementById('harga').value = document.getElementById('harga').value.replace(/,/g, '');
            document.getElementById('dp1').value = document.getElementById('dp1').value.replace(/,/g, '');
            document.getElementById('dp2').value = document.getElementById('dp2').value.replace(/,/g, '');
            document.getElementById('dp3').value = document.getElementById('dp3').value.replace(/,/g, '');
        }
    </script>
</body>

</html>