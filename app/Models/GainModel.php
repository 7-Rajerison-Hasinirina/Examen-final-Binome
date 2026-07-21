<?php

namespace App\Models;

use CodeIgniter\Model;

class GainModel extends Model
{
    protected $table = 'gain';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_type_operation',
        'montant',
        'operateur',
        'date',
    ];

    public function getTotalGainByOperateur(string $operateur): array
    {
        return $this->getTotalGainByOperateurs([$operateur]);
    }

    public function getTotalGainByOperateurs(array $operateurs): array
    {
        return $this->select(
            "COALESCE(SUM(montant), 0) AS total_gain,\n            COALESCE(SUM(CASE WHEN id_type_operation = 1 THEN montant ELSE 0 END), 0) AS total_depot_gain,\n            COALESCE(SUM(CASE WHEN id_type_operation = 2 THEN montant ELSE 0 END), 0) AS total_retrait_gain,\n            COALESCE(SUM(CASE WHEN id_type_operation = 3 THEN montant ELSE 0 END), 0) AS total_transfert_gain",
            false
        )
            ->whereIn('operateur', $operateurs)
            ->first();
    }
}
