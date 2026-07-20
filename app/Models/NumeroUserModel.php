<?php

namespace App\Models;

use CodeIgniter\Model;

class NumeroUserModel extends Model
{
    protected $table = 'numero_user';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_prefixe',
        'numero',
        'id_user',
        'date_creation'
    ];

    protected $validationRules = [
        'id_prefixe' => 'required|integer',
        'numero' => 'required|min_length[7]',
        'id_user' => 'required|integer'
    ];

    protected $validationMessages = [
        'id_prefixe' => [
            'required' => 'Le préfixe est obligatoire.',
            'integer' => 'Le préfixe doit être un nombre entier.'
        ],
        'numero' => [
            'required' => 'Le numéro est obligatoire.',
            'min_length' => 'Le numéro doit contenir au moins 7 chiffres.'
        ],
        'id_user' => [
            'required' => 'L\'utilisateur est obligatoire.',
            'integer' => 'L\'utilisateur doit être un nombre entier.'
        ]
    ];

    /**
     * Trouver un utilisateur par préfixe et numéro
     */
    public function findByPrefixeAndNumero($idPrefixe, $numero)
    {
        return $this->select('numero_user.*, users.id as user_id, users.nom, users.id_role')
            ->join('users', 'numero_user.id_user = users.id', 'left')
            ->where('numero_user.id_prefixe', $idPrefixe)
            ->where('numero_user.numero', $numero)
            ->first();
    }

    public function findByNumero(string $numero): ?array
    {
        return $this->where('numero', $numero)->first();
    }

    public function findByNumeroAndUser(string $numero, int $idUser): ?array
    {
        return $this->where('numero', $numero)
            ->where('id_user', $idUser)
            ->first();
    }

    public function findByNumeroWithOperateur(string $numero): ?array
    {
        return $this->select('numero_user.*, operateur.prefixe, operateur.operateur')
            ->join('operateur', 'numero_user.id_prefixe = operateur.id', 'left')
            ->where('numero_user.numero', $numero)
            ->first();
    }

    public function findByNumeroAndUserWithOperateur(string $numero, int $idUser): ?array
    {
        return $this->select('numero_user.*, operateur.prefixe, operateur.operateur')
            ->join('operateur', 'numero_user.id_prefixe = operateur.id', 'left')
            ->where('numero_user.numero', $numero)
            ->where('numero_user.id_user', $idUser)
            ->first();
    }

    /**
     * Récupérer tous les numéros d'un utilisateur
     */
    public function getNumerosByUser($idUser)
    {
        return $this->select('numero_user.*, operateur.prefixe, operateur.operateur')
            ->join('operateur', 'numero_user.id_prefixe = operateur.id', 'left')
            ->where('numero_user.id_user', $idUser)
            ->findAll();
    }
}
