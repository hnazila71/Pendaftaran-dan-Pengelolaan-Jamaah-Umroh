<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= esc($fieldLabel) ?></title>
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

        .edit-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h1 {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

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

        input[type="text"]:focus {
            border-color: #007BFF;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
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

        .back-button {
            display: block;
            margin-top: 15px;
            color: #007BFF;
            text-decoration: none;
            font-size: 0.9em;
        }

        .back-button:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="edit-container">
        <h1>Edit <?= esc($fieldLabel) ?> untuk <?= esc($transaksi['nama_jamaah']) ?></h1>

        <form action="<?= site_url('dashboard/update-dp/' . $transaksi['id']) ?>" method="post" onsubmit="removeCommas()">
            <input type="hidden" name="dpField" value="<?= esc($dpField) ?>">

            <label for="dpValue"><?= esc($fieldLabel) ?>:</label>
            <?php
            $formattedValue = number_format((float) $transaksi[$dpField], 0, '.', ',');
            ?>
            <input type="text" id="dpValue" name="dpValue" value="<?= esc($formattedValue) ?>" required oninput="formatNumber(this)">

            <button type="submit" class="submit-button">Simpan</button>
        </form>

        <!-- Tombol kembali ke detail program -->
        <a href="<?= site_url('dashboard/program/' . $transaksi['id_program']) ?>" class="back-button">Kembali ke Detail Program</a>
    </div>

    <script>
        function formatNumber(input) {
            // Hapus semua koma
            let value = input.value.replace(/,/g, '');

            // Format ulang dengan koma setiap 3 digit
            input.value = parseFloat(value).toLocaleString('en-US');
        }

        function removeCommas() {
            // Menghapus koma pada input sebelum submit form
            let dpValue = document.getElementById('dpValue');
            dpValue.value = dpValue.value.replace(/,/g, '');
        }
    </script>
</body>

</html>