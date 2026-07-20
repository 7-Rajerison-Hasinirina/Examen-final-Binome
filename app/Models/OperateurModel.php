<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table = 'operateur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'prefixe',
        'operateur'
    ];

    protected $validationRules = [
        'prefixe' => 'required|max_length[3]|is_unique[operateur.prefixe]',
        'operateur' => 'required|min_length[2]'
    ];

    protected $validationMessages = [
        'prefixe' => [
            'required' => 'Le préfixe est obligatoire.',
            'max_length' => 'Le préfixe ne doit pas dépasser 3 caractères.',
            'is_unique' => 'Ce préfixe existe déjà.'
        ],
        'operateur' => [
            'required' => 'Le nom de l\'opérateur est obligatoire.',
            'min_length' => 'Le nom de l\'opérateur doit contenir au moins 2 caractères.'
        ]
    ];
}