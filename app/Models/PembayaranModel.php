<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'transaksi_pembayaran';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'transaksi_id',
        'nominal',
        'keterangan',
        'dibayar_pada',
    ];
}
