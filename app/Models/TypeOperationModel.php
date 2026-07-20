<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table = 'type_operation';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'libelle'
    ];

    protected $validationRules = [
        'libelle' => 'required|min_length[2]|is_unique[type_operation.libelle]'
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libellé est obligatoire.',
            'min_length' => 'Le libellé doit contenir au moins 2 caractères.',
            'is_unique' => 'Ce type d\'opération existe déjà.'
        ]
    ];

    /**
     * Récupérer toutes les opérations
     */
    public function getAllOperations()
    {
        return $this->findAll();
    }

    /**
     * Récupérer une opération par son ID
     */
    public function getOperationById($id)
    {
        return $this->find($id);
    }
}
