<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            width: 100%;
            max-width: 600px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        h1 {
            color: #333;
            margin: 0;
        }

        .logout-button {
            font-size: 1em;
            color: #fff;
            background-color: #e74c3c;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #c0392b;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .actions a {
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007BFF;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .actions a:hover {
            background-color: #0056b3;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .program-list {
            list-style-type: none;
            padding: 0;
            width: 100%;
            max-width: 600px;
        }

        .program-list li {
            background-color: #ffffff;
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .program-list li:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .program-list a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
            transition: color 0.2s ease;
        }

        .program-list a:hover {
            color: #0056b3;
        }

        .program-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .program-date {
            color: #555;
            font-size: 0.9em;
        }

        .table-container {
            width: 100%;
            max-width: 600px;
            overflow-y: auto;
            height: 300px;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f7fa;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .btn {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pengeluaran</h1>

        <!-- Menampilkan pengeluaran yang sudah ada -->
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengeluaran as $item): ?>
                        <tr>
                            <td><?= esc($item['tanggal']); ?></td>
                            <td><?= esc($item['keterangan']); ?></td>
                            <td><?= number_format($item['jumlah'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Menampilkan Total Pengeluaran -->
        <h3>Total Pengeluaran: <?= number_format($total, 0, ',', '.'); ?></h3>

        <!-- Form untuk menambahkan pengeluaran -->
        <h3>Tambah Pengeluaran</h3>
        <form action="/keuangan/save" method="post">
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
        </form>

        <!-- Link kembali ke Dashboard -->
        <br>
        <a href="/dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</body>
</html>
