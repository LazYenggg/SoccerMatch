<?php

namespace App\Models;

use CodeIgniter\Model;

class TimModel extends Model
{
    protected $table            = 'tim';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_tim', 'kota', 'stadion', 'liga', 'logo'];
}