<?php

namespace App\Models;

use CodeIgniter\Model;

class JamaahModel extends Model
{
    protected $table = 'jamaah';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_jamaah'];
}
