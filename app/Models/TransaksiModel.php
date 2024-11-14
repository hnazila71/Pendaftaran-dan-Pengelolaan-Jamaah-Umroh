<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_jamaah',
        'id_program',
        'harga',
        'dp1',
        'dp2',
        'dp3',
        'dp1_time_edit',
        'dp2_time_edit',
        'dp3_time_edit'
    ];
}
