<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .keuangan-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .keuangan-container h1 {
            text-align: center;
        }

        .keuangan-container table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .keuangan-container th,
        .keuangan-container td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: right;
        }

        .keuangan-container th {
            background-color: #007BFF;
            color: white;
        }

        .total-row td {
            font-weight: bold;
            color: #007BFF;
            background-color: #f1f1f1;
        }

        .status-row td {
            font-weight: bold;
            color: #FFFFFF;
            background-color: #28a745;
        }

        .status-row.hutang td {
            background-color: #dc3545;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="keuangan-container">
        <h1>Ringkasan Keuangan</h1>
        <table>
            <tr>
                <th>Total Harga</th>
                <td><?= number_format($totalHarga, 2) ?></td>
            </tr>
            <tr>
                <th>Total Kekurangan</th>
                <td><?= number_format($totalKekurangan, 2) ?></td>
            </tr>
            <tr>
                <th>Pemasukan</th>
                <td><strong><?= number_format($pemasukan, 2) ?></strong></td>
            </tr>
            <tr class="total-row">
                <td colspan="2">Total: <?= number_format($totalHarga + $totalKekurangan, 2) ?></td>
            </tr>
            <?php
            // Tentukan status keuangan
            $statusKeuangan = $pemasukan >= $totalHarga ? "Surplus" : "Hutang";
            $statusClass = $pemasukan >= $totalHarga ? "status-row" : "status-row hutang";
            $selisih = abs($pemasukan - $totalHarga);
            ?>
            <tr class="<?= $statusClass ?>">
                <td colspan="2"><?= $statusKeuangan ?>: <?= number_format($selisih, 2) ?></td>
            </tr>
        </table>

        <!-- Tombol kembali ke dashboard -->
        <a href="<?= site_url('dashboard') ?>" class="back-button">Kembali ke Dashboard</a>
    </div>
</body>

</html>