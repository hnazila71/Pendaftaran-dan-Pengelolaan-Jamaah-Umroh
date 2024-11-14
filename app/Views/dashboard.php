<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razek Pekajangan</title>
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
    </style>
</head>

<body>
    <div class="header">
        <h1>Razek Pekajangan</h1>
        <a href="<?= site_url('logout') ?>" class="logout-button">Logout</a>
    </div>

    <div class="actions">
        <a href="<?= site_url('dashboard/add-jamaah') ?>">Tambah Jamaah</a>
        <a href="<?= site_url('dashboard/add-program') ?>">Tambah Program</a>
        <a href="<?= site_url('dashboard/add-transaksi') ?>">Tambah Transaksi</a>
        <a href="<?= site_url('keuangan') ?>" class="keuangan-button">Lihat Keuangan</a>
    </div>

    <h2>Daftar Program</h2>
    <ul class="program-list">
        <?php if (!empty($programs)): ?>
            <?php foreach ($programs as $program): ?>
                <li>
                    <div class="program-title"><?= esc($program['nama_program']) ?></div>
                    <div class="program-date"><?= esc($program['tanggal_program']) ?></div>
                    <a href="<?= site_url('dashboard/program/' . $program['id']) ?>">Lihat Detail dan Transaksi</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">Tidak ada program yang tersedia saat ini.</p>
        <?php endif; ?>
    </ul>
</body>

</html>