<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table= 'epargne';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_user',
        'montant'
    ];
}