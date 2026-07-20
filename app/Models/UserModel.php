<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nom',
        'id_role'
    ];

    protected $validationRules = [
        'nom' => 'required|min_length[3]',
        'id_role' => 'required|integer'
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom est obligatoire.',
            'min_length' => 'Le nom doit contenir au moins 3 caractères.'
        ],
        'id_role' => [
            'required' => 'Le rôle est obligatoire.',
            'integer' => 'Le rôle doit être un nombre entier.'
        ]
    ];
}