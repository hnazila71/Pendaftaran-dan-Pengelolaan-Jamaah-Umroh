<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'password',
        'email',
        'google_id',
        'role',
        'approval_status',
        'approved_by',
        'approved_at',
        'is_super_admin',
    ];
}
