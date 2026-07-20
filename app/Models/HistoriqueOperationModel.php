<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueOperationModel extends Model
{
    protected $table = 'historique_operation';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'date',
        'id_user',
        'id_operation',
        'valeur',
        'numero_source',
        'numero_destination',
        'reference',
        'sens',
    ];

    public function getOperationsByUser(int $userId, int $limit = 0): array
    {
        $builder = $this->select('historique_operation.*, type_operation.libelle AS type')
            ->join('type_operation', 'type_operation.id = historique_operation.id_operation', 'left')
            ->where('historique_operation.id_user', $userId)
            ->orderBy('historique_operation.date', 'DESC')
            ->orderBy('historique_operation.id', 'DESC');

        return $limit > 0 ? $builder->findAll($limit) : $builder->findAll();
    }

    public function getOperationsByNumero(string $numero): array
    {
        return $this->select('historique_operation.*, type_operation.libelle AS type')
            ->join('type_operation', 'type_operation.id = historique_operation.id_operation', 'left')
            ->groupStart()
                ->where('historique_operation.numero_source', $numero)
                ->orWhere('historique_operation.numero_destination', $numero)
            ->groupEnd()
            ->orderBy('historique_operation.date', 'DESC')
            ->orderBy('historique_operation.id', 'DESC')
            ->findAll();
    }

    public function getBalanceByNumero(string $numero): float
    {
        $row = $this->select(
            "COALESCE(SUM(CASE
                WHEN historique_operation.numero_destination = " . $this->db->escape($numero) . " AND COALESCE(historique_operation.sens, 'entree') = 'entree' THEN historique_operation.valeur
                WHEN historique_operation.numero_source = " . $this->db->escape($numero) . " AND COALESCE(historique_operation.sens, 'sortie') = 'sortie' THEN -historique_operation.valeur
                ELSE 0
            END), 0) AS solde",
            false
        )
            ->first();

        return (float) ($row['solde'] ?? 0);
    }

    public function getBalancesByNumbers(array $numeros): array
    {
        return array_map(
            function (array $numero): array {
                $numero['solde'] = $this->getBalanceByNumero((string) $numero['numero']);

                return $numero;
            },
            $numeros
        );
    }
}