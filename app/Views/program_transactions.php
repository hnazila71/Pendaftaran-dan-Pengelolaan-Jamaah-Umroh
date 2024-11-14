<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Program dan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7fa;
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .transactions {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .transactions table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .transactions th,
        .transactions td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .transactions th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
        }

        .edit-button {
            padding: 5px 10px;
            margin-top: 5px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9em;
        }

        .edit-button:hover {
            background-color: #218838;
        }

        .success-message {
            color: #28a745;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
            font-size: 0.9em;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Detail Program</h1>
        <p><strong>Nama Program:</strong> <?= esc($program['nama_program']) ?></p>
        <p><strong>Tanggal Program:</strong> <?= esc($program['tanggal_program']) ?></p>
        <a href="<?= site_url('dashboard') ?>" class="back-link">Kembali ke Dashboard</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <p class="success-message"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <div class="transactions">
        <h2>Daftar Transaksi untuk Program Ini</h2>
        <?php if (!empty($transaksi)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Jamaah</th>
                        <th>Harga</th>
                        <th>DP 1</th>
                        <th>Waktu Edit DP 1</th>
                        <th>DP 2</th>
                        <th>Waktu Edit DP 2</th>
                        <th>DP 3</th>
                        <th>Waktu Edit DP 3</th>
                        <th>Kekurangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td><?= esc($t['nama_jamaah']) ?></td>
                            <td><?= number_format($t['harga'], 0, '.', ',') ?></td>

                            <!-- DP1 dan waktu editnya -->
                            <td>
                                <?= number_format($t['dp1'], 0, '.', ',') ?><br>
                                <a href="<?= site_url('dashboard/edit-dp1/' . $t['id']) ?>" class="edit-button">Edit DP1</a>
                            </td>
                            <td><?= esc($t['dp1_time_edit'] ?? '-') ?></td>

                            <!-- DP2 dan waktu editnya -->
                            <td>
                                <?= number_format($t['dp2'], 0, '.', ',') ?><br>
                                <a href="<?= site_url('dashboard/edit-dp2/' . $t['id']) ?>" class="edit-button">Edit DP2</a>
                            </td>
                            <td><?= esc($t['dp2_time_edit'] ?? '-') ?></td>

                            <!-- DP3 dan waktu editnya -->
                            <td>
                                <?= number_format($t['dp3'], 0, '.', ',') ?><br>
                                <a href="<?= site_url('dashboard/edit-dp3/' . $t['id']) ?>" class="edit-button">Edit DP3</a>
                            </td>
                            <td><?= esc($t['dp3_time_edit'] ?? '-') ?></td>

                            <!-- Kekurangan -->
                            <td><?= number_format($t['kekurangan'], 0, '.', ',') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada transaksi untuk program ini.</p>
        <?php endif; ?>
    </div>
</body>

</html>