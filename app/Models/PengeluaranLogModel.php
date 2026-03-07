<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranLogModel extends Model
{
    protected $table = 'pengeluaran_log';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'pengeluaran_id',
        'action',
        'edited_by',
        'edited_at',
        'old_tanggal',
        'new_tanggal',
        'old_keterangan',
        'new_keterangan',
        'old_jumlah',
        'new_jumlah',
    ];
}
