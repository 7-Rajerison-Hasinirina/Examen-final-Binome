<?php

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\UserModel;
use App\Models\NumeroUserModel;

class OperateurController extends BaseController
{
    protected $operateurModel;
    protected $userModel;
    protected $numeroUserModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
        $this->userModel = new UserModel();
        $this->numeroUserModel = new NumeroUserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Connexion',
            'prefixes' => $this->operateurModel->getAllOperateurs()
        ];

        return view('login', $data);
    }

    /**
     * Valider et authentifier l'utilisateur
     */
    public function valider()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/');
        }

        $nom = $this->request->getPost('nom');
        $idPrefixe = $this->request->getPost('id_prefixe');
        $numero = $this->request->getPost('numero');

        // Vérifier si le numéro existe déjà dans numero_user
        $existingNumero = $this->numeroUserModel->findByPrefixeAndNumero($idPrefixe, $numero);

        if ($existingNumero) {
            // L'utilisateur existe déjà
            $userId = $existingNumero['user_id'];
            $idRole = $existingNumero['id_role'];
            $nomUser = $existingNumero['nom'];

            // Stocker les infos en session
            session()->set([
                'user_id' => $userId,
                'nom' => $nomUser,
                'id_role' => $idRole,
                'numero' => $numero,
                'id_prefixe' => $idPrefixe
            ]);

            // Redirection selon le rôle
            if ($idRole == 2) {
                // Client
                return redirect()->to('/client-office');
            } elseif ($idRole == 3) {
                // Opérateur
                return redirect()->to('/operateur-office');
            } else {
                // Admin ou autre
                return redirect()->to('/');
            }
        } else {
            // Créer un nouvel utilisateur (client par défaut - id_role = 2)
            $newUser = [
                'nom' => $nom,
                'id_role' => 2  // Client
            ];

            $userId = $this->userModel->insert($newUser, true);

            if (!$userId) {
                session()->setFlashdata('error', 'Erreur lors de la création de l\'utilisateur.');
                return redirect()->back()->withInput();
            }

            // Créer l'entrée numero_user
            $newNumero = [
                'id_prefixe' => $idPrefixe,
                'numero' => $numero,
                'id_user' => $userId,
                'date_creation' => date('Y-m-d H:i:s')
            ];

            $this->numeroUserModel->insert($newNumero);

            // Stocker les infos en session
            session()->set([
                'user_id' => $userId,
                'nom' => $nom,
                'id_role' => 2,
                'numero' => $numero,
                'id_prefixe' => $idPrefixe
            ]);

            return redirect()->to('/client-office');
        }
    }
}