<?php

namespace App\Controllers;

class ClientOfficeController extends BaseController
{
    /**
     * Afficher le tableau de bord client
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
            'title' => 'Espace Client',
            'nom' => $nom,
            'numero' => $numero,
            'user_id' => $userId
        ];

        return view('ClientOffice', $data);
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
