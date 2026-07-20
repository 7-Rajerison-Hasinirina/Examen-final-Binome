<?php

namespace App\Models;

use CodeIgniter\Model;

class NormeImcModel extends Model
{
    protected $table = 'norme_imc';
    protected $primaryKey = 'id_norme';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['libelle', 'v_min', 'v_max'];

    protected $validationRules = [
        'libelle' => 'required|min_length[2]|max_length[50]',
        'v_min' => 'required|decimal',
        'v_max' => 'required|decimal',
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libelle est obligatoire.',
            'min_length' => 'Le libelle doit contenir au moins 2 caracteres.',
            'max_length' => 'Le libelle ne doit pas depasser 50 caracteres.',
        ],
        'v_min' => [
            'required' => 'La valeur minimale est obligatoire.',
            'decimal' => 'La valeur minimale doit etre un nombre decimal.',
        ],
        'v_max' => [
            'required' => 'La valeur maximale est obligatoire.',
            'decimal' => 'La valeur maximale doit etre un nombre decimal.',
        ],
    ];
}
