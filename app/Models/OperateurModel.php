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

    public function getAllOperateurs()
    {
        return $this->orderBy('prefixe', 'ASC')->findAll();
    }

    public function getPrefixes()
    {
        return $this->orderBy('prefixe', 'ASC')->findAll();
    }

    public function getPrefixById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function savePrefix($data, $id = null)
    {
        if ($id) {
            return $this->update($id, $data);
        }

        return $this->insert($data);
    }

    public function getOperationTypes()
    {
        return $this->db->table('type_operation')
            ->orderBy('libelle', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getOperationTypeById($id)
    {
        return $this->db->table('type_operation')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    public function saveOperationType($data, $id = null)
    {
        $table = $this->db->table('type_operation');

        if ($id) {
            return $table->where('id', $id)->update($data);
        }

        return $table->insert($data);
    }

    public function getBaremes($typeId = null, $operateurId = null)
    {
        $builder = $this->db->table('bareme_frais');
        $builder->select('bareme_frais.id, bareme_frais.intervalle1, bareme_frais.intervalle2, bareme_frais.frais, type_operation.libelle as type_operation, operateur.prefixe as prefixe_operateur');
        $builder->join('type_operation', 'type_operation.id = bareme_frais.id_type_operation', 'left');
        $builder->join('operateur', 'operateur.id = bareme_frais.id_operateur', 'left');
        $builder->orderBy('bareme_frais.id', 'ASC');

        if ($typeId) {
            $builder->where('bareme_frais.id_type_operation', $typeId);
        }

        if ($operateurId) {
            $builder->where('bareme_frais.id_operateur', $operateurId);
        }

        return $builder->get()->getResultArray();
    }

    public function getBaremeById($id)
    {
        return $this->db->table('bareme_frais')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    public function saveBareme($data, $id = null)
    {
        $table = $this->db->table('bareme_frais');

        if ($id) {
            return $table->where('id', $id)->update($data);
        }

        return $table->insert($data);
    }

    public function getClientSituations()
    {
        $builder = $this->db->table('users');
        $builder->select('users.id, users.nom, numero_user.numero, operateur.prefixe');
        $builder->join('numero_user', 'numero_user.id_user = users.id', 'left');
        $builder->join('operateur', 'operateur.id = numero_user.id_prefixe', 'left');
        $builder->where('users.id_role', 2);
        $builder->orderBy('users.nom', 'ASC');

        $clients = $builder->get()->getResultArray();

        foreach ($clients as &$client) {
            $histories = $this->db->table('historique_operation')
                ->where('id_user', $client['id'])
                ->get()
                ->getResultArray();

            $solde = 0.0;
            foreach ($histories as $history) {
                $opId = (int) ($history['id_operation'] ?? 0);
                if ($opId === 1) {
                    $solde += (float) ($history['valeur'] ?? 0);
                } else {
                    $solde -= (float) ($history['valeur'] ?? 0);
                }
            }

            $client['solde'] = number_format($solde, 2, ',', ' ');
            $client['reference'] = (!empty($client['prefixe']) && !empty($client['numero'])) ? $client['prefixe'] . ' ' . $client['numero'] : 'Aucune référence';
        }

        return $clients;
    }
}