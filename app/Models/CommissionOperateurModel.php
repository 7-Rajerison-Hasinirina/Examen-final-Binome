<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionOperateurModel extends Model
{
    protected $table = 'commission_operateur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_operateur',
        'pourcentage',
    ];

    protected $validationRules = [
        'id_operateur' => 'required|integer',
        'pourcentage' => 'required|decimal|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'id_operateur' => [
            'required' => 'L\'opérateur est requis.',
            'integer' => 'L\'opérateur doit être un entier.',
        ],
        'pourcentage' => [
            'required' => 'Le pourcentage est requis.',
            'decimal' => 'Le pourcentage doit être un nombre.',
            'greater_than_equal_to' => 'Le pourcentage doit être supérieur ou égal à 0.',
        ],
    ];

    public function getByOperateur(int $idOperateur): ?array
    {
        return $this->where('id_operateur', $idOperateur)->first();
    }
}
