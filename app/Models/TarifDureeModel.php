<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifDureeModel extends Model
{
    protected $table = 'tarif_duree';
    protected $primaryKey = 'id_tarif';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['libelle', 'duree_min', 'duree_max', 'coefficient'];

    protected $validationRules = [
        'libelle' => 'required|min_length[2]|max_length[50]',
        'duree_min' => 'required|integer',
        'duree_max' => 'permit_empty|integer',
        'coefficient' => 'required|decimal',
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libelle est obligatoire.',
            'min_length' => 'Le libelle doit contenir au moins 2 caracteres.',
            'max_length' => 'Le libelle ne doit pas depasser 50 caracteres.',
        ],
        'duree_min' => [
            'required' => 'La duree minimale est obligatoire.',
            'integer' => 'La duree minimale doit etre un entier.',
        ],
        'duree_max' => [
            'integer' => 'La duree maximale doit etre un entier.',
        ],
        'coefficient' => [
            'required' => 'Le coefficient est obligatoire.',
            'decimal' => 'Le coefficient doit etre un nombre decimal.',
        ],
    ];
}
