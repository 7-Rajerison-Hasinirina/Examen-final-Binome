<?php

namespace App\Controllers;

use App\Models\ProduitModel;
use CodeIgniter\Controller;

class ProduitController extends Controller
{
    public function index()
    {
        $model = new ProduitModel();
        $data['produits'] = $model->findAll();
        echo view('produits/index', $data);
    }
}
