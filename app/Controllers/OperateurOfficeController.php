<?php

namespace App\Controllers;

class OperateurOfficeController extends BaseController
{
    /**
     * Afficher le tableau de bord opérateur
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        // Récupérer les infos de l'utilisateur depuis la session
        $userId = session()->get('user_id');
        $nom = session()->get('nom');
        $numero = session()->get('numero');

        $data = [
            'title' => 'Espace Opérateur',
            'nom' => $nom,
            'numero' => $numero,
            'user_id' => $userId
        ];

        return view('OperateurOffice', $data);
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
