<?php

namespace App\Controllers;

use App\Models\OperateurModel;

class OperateurOfficeController extends BaseController
{
    /**
     * Afficher le tableau de bord opérateur
     */
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        $model = new OperateurModel();
        $request = $this->request;

        if ($request->getGet('action')) {
            $action = $request->getGet('action');

            if ($action === 'save_prefix') {
                $id = $request->getGet('id');
                $prefixe = trim($request->getGet('prefixe') ?? '');
                $operateur = trim($request->getGet('operateur') ?? '');

                if ($prefixe !== '' && $operateur !== '') {
                    $model->savePrefix([
                        'prefixe' => $prefixe,
                        'operateur' => $operateur
                    ], $id ?: null);

                    session()->setFlashdata('success', $id ? 'Préfixe modifié avec succès.' : 'Préfixe ajouté avec succès.');
                } else {
                    session()->setFlashdata('error', 'Veuillez remplir tous les champs du préfixe.');
                }
            }

            if ($action === 'save_operation') {
                $id = $request->getGet('id');
                $libelle = trim($request->getGet('libelle') ?? '');

                if ($libelle !== '') {
                    $model->saveOperationType(['libelle' => $libelle], $id ?: null);
                    session()->setFlashdata('success', $id ? 'Type d\'opération modifié avec succès.' : 'Type d\'opération ajouté avec succès.');
                } else {
                    session()->setFlashdata('error', 'Veuillez saisir le nom du type d\'opération.');
                }
            }

            if ($action === 'save_bareme') {
                $id = $request->getGet('id');
                $data = [
                    'id_type_operation' => (int) ($request->getGet('id_type_operation') ?? 0),
                    'intervalle1' => (float) ($request->getGet('intervalle1') ?? 0),
                    'intervalle2' => (float) ($request->getGet('intervalle2') ?? 0),
                    'frais' => (float) ($request->getGet('frais') ?? 0),
                    'id_operateur' => (int) ($request->getGet('id_operateur') ?? 0)
                ];

                if ($data['id_type_operation'] > 0 && $data['id_operateur'] > 0) {
                    $model->saveBareme($data, $id ?: null);
                    session()->setFlashdata('success', $id ? 'Barème modifié avec succès.' : 'Barème ajouté avec succès.');
                } else {
                    session()->setFlashdata('error', 'Veuillez choisir un type d\'opération et un opérateur.');
                }
            }
        }

        $userId = session()->get('user_id');
        $nom = session()->get('nom');
        $numero = session()->get('numero');

        $prefixes = $model->getPrefixes();
        $operationTypes = $model->getOperationTypes();
        $operateurs = $model->getAllOperateurs();
        $selectedTypeId = $request->getGet('type_id');
        $selectedOperateurId = $request->getGet('operateur_id');
        $baremes = $model->getBaremes($selectedTypeId, $selectedOperateurId);
        $clients = $model->getClientSituations();
        $editPrefix = $request->getGet('edit_prefix_id') ? $model->getPrefixById($request->getGet('edit_prefix_id')) : null;
        $editOperation = $request->getGet('edit_operation_id') ? $model->getOperationTypeById($request->getGet('edit_operation_id')) : null;
        $editBareme = $request->getGet('edit_bareme_id') ? $model->getBaremeById($request->getGet('edit_bareme_id')) : null;

        $data = [
            'title' => 'Espace Opérateur',
            'nom' => $nom,
            'numero' => $numero,
            'user_id' => $userId,
            'prefixes' => $prefixes,
            'operationTypes' => $operationTypes,
            'operateurs' => $operateurs,
            'baremes' => $baremes,
            'clients' => $clients,
            'editPrefix' => $editPrefix,
            'editOperation' => $editOperation,
            'editBareme' => $editBareme,
            'selectedTypeId' => $selectedTypeId,
            'selectedOperateurId' => $selectedOperateurId
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
