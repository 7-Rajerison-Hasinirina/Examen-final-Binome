<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table = 'bareme_frais';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_type_operation',
        'intervalle1',
        'intervalle2',
        'frais',
        'id_operateur',
    ];

    public function getFraisForOperation(int $idOperateur, int $idTypeOperation, float $montant): float
    {
        $row = $this->where('id_operateur', $idOperateur)
            ->where('id_type_operation', $idTypeOperation)
            ->where('intervalle1 <=', $montant)
            ->where('intervalle2 >=', $montant)
            ->first();

        return $row ? (float) $row['frais'] : 0.0;
    }
}
