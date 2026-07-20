<?php

namespace App\Controllers;

use App\Models\OperateurModel;

class OperateurController extends BaseController
{
    protected $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Connexion',
            'prefixes' => $this->operateurModel->getAllOperateurs()
        ];

        return view('login', $data);
    }
}