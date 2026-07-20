<?php

namespace App\Controllers;

use App\Models\CodeModel;
use App\Models\CodeUserModel;
use App\Models\UserModel;

class GestionPorteMonnaie extends BaseController
{
    public function index()
    {
        $user = session()->get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->to('/')->with('error', 'Session expiree.');
        }

        $userModel = new UserModel();
        $codeUserModel = new CodeUserModel();

        $currentUser = $userModel->find($user['id']);
        $historique = $codeUserModel
            ->select('code_user.*, code.libelle, code.montant')
            ->join('code', 'code.id_code = code_user.id_code')
            ->where('code_user.id_user', $user['id'])
            ->orderBy('code_user.date', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Porte-monnaie',
            'user' => $currentUser,
            'historique' => $historique,
        ];

        return view('porte_monnaie', $data);
    }

    public function utiliserCode()
    {
        $user = session()->get('user');
        if (!$user || !isset($user['id'])) {
            return redirect()->to('/')->with('error', 'Session expiree.');
        }

        $codeInput = trim((string) $this->request->getPost('code'));
        if ($codeInput === '') {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un code.');
        }

        $codeModel = new CodeModel();
        $codeUserModel = new CodeUserModel();
        $userModel = new UserModel();

        $code = $codeModel->where('libelle', $codeInput)->first();
        if (!$code) {
            return redirect()->back()->withInput()->with('error', 'Code invalide.');
        }

        $today = date('Y-m-d');
        if (!empty($code['date_expiration']) && $code['date_expiration'] < $today) {
            return redirect()->back()->withInput()->with('error', 'Code expire.');
        }

        $alreadyUsed = $codeUserModel
            ->where('id_code', $code['id_code'])
            ->where('id_user', $user['id'])
            ->countAllResults() > 0;
        if ($alreadyUsed) {
            return redirect()->back()->withInput()->with('error', 'Code deja utilise.');
        }

        $currentUser = $userModel->find($user['id']);
        if (!$currentUser) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }

        $newBalance = (float) $currentUser['porte_monnaie'] + (float) $code['montant'];
        $userModel->update($user['id'], ['porte_monnaie' => $newBalance]);

        $codeUserModel->insert([
            'id_code' => $code['id_code'],
            'id_user' => $user['id'],
            'date' => $today,
        ]);

        $user['porte_monnaie'] = $newBalance;
        session()->set('user', $user);

        return redirect()->to('/porte-monnaie')->with('success', 'Code applique.');
    }
}
