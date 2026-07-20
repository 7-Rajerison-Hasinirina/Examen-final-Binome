<?php

namespace App\Controllers;

use App\Models\NumeroUserModel;
use App\Models\TypeOperationModel;

class ClientOfficeController extends BaseController
{
    protected $numeroUserModel;
    protected $typeOperationModel;

    public function __construct()
    {
        $this->numeroUserModel = new NumeroUserModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    /**
     * Afficher le tableau de bord client
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');
        $nom = session()->get('nom');

        $data = [
            'title' => 'Espace Client',
            'nom' => $nom,
            'user_id' => $userId,
            'numeros' => $this->numeroUserModel->getNumerosByUser($userId),
            'operations' => $this->typeOperationModel->getAllOperations()
        ];

        return view('ClientOffice', $data);
    }

    /**
     * Afficher le solde
     */
    public function solde()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');

        $data = [
            'title' => 'Voir le Solde',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $this->numeroUserModel->getNumerosByUser($userId),
            'solde' => 0 // À implémenter avec la logique métier
        ];

        return view('client/solde', $data);
    }

    /**
     * Afficher le formulaire de retrait
     */
    public function retrait()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');

        $data = [
            'title' => 'Faire un Retrait',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $this->numeroUserModel->getNumerosByUser($userId)
        ];

        return view('client/retrait', $data);
    }

    /**
     * Traiter le retrait
     */
    public function traiterRetrait()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $montant = $this->request->getPost('montant');
        $numeroRetrait = $this->request->getPost('numero');

        // À implémenter avec la logique métier
        session()->setFlashdata('success', 'Retrait de ' . $montant . ' Ar effectué avec succès.');
        return redirect()->to('/client-office');
    }

    /**
     * Afficher le formulaire de transfert
     */
    public function transfert()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');

        $data = [
            'title' => 'Faire un Transfert',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $this->numeroUserModel->getNumerosByUser($userId)
        ];

        return view('client/transfert', $data);
    }

    /**
     * Traiter le transfert
     */
    public function traiterTransfert()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $montant = $this->request->getPost('montant');
        $numeroDestination = $this->request->getPost('numero_destination');

        // À implémenter avec la logique métier
        session()->setFlashdata('success', 'Transfert de ' . $montant . ' Ar effectué avec succès.');
        return redirect()->to('/client-office');
    }

    /**
     * Afficher l'historique
     */
    public function historique()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');

        $data = [
            'title' => 'Historique des Opérations',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $this->numeroUserModel->getNumerosByUser($userId),
            'operations' => [] // À récupérer depuis la base de données
        ];

        return view('client/historique', $data);
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
