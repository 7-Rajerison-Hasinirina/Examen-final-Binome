<?php

namespace App\Controllers;

use App\Models\HistoriqueOperationModel;
use App\Models\NumeroUserModel;
use App\Models\OperateurModel;
use App\Models\TypeOperationModel;
use App\Models\UserModel;

class OperateurOfficeController extends BaseController
{
    protected $operateurModel;
    protected $userModel;
    protected $numeroUserModel;
    protected $historiqueOperationModel;
    protected $typeOperationModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
        $this->userModel = new UserModel();
        $this->numeroUserModel = new NumeroUserModel();
        $this->historiqueOperationModel = new HistoriqueOperationModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    private function guardOperateurSession(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        if ((int) session()->get('id_role') !== 3) {
            return redirect()->to('/');
        }

        return null;
    }

    private function normalizePrefixe(?string $prefixe): string
    {
        return preg_replace('/\D+/', '', (string) $prefixe) ?? '';
    }

    private function getValidationError(): string
    {
        $errors = $this->validator?->getErrors() ?? [];

        return $errors ? implode(' ', $errors) : 'Données invalides.';
    }

    private function getDashboardData(): array
    {
        $prefixes = $this->operateurModel->orderBy('prefixe', 'ASC')->findAll();
        $typesOperations = $this->typeOperationModel->orderBy('libelle', 'ASC')->findAll();
        $recentOperations = $this->historiqueOperationModel
            ->select('historique_operation.*, type_operation.libelle AS type, users.nom AS utilisateur')
            ->join('type_operation', 'type_operation.id = historique_operation.id_operation', 'left')
            ->join('users', 'users.id = historique_operation.id_user', 'left')
            ->orderBy('historique_operation.date', 'DESC')
            ->orderBy('historique_operation.id', 'DESC')
            ->findAll(8);

        $gainStats = $this->historiqueOperationModel
            ->select(
                "COALESCE(SUM(CASE WHEN COALESCE(sens, 'entree') = 'entree' THEN valeur ELSE 0 END), 0) AS total_entrees,\n                COALESCE(SUM(CASE WHEN COALESCE(sens, 'sortie') = 'sortie' THEN valeur ELSE 0 END), 0) AS total_sorties,\n                COALESCE(SUM(CASE WHEN COALESCE(sens, 'entree') = 'entree' THEN valeur ELSE -valeur END), 0) AS gain_net",
                false
            )
            ->first();

        $accountStats = [
            'total_users' => (new UserModel())->countAllResults(),
            'clients' => (new UserModel())->where('id_role', 2)->countAllResults(),
            'operateurs' => (new UserModel())->where('id_role', 3)->countAllResults(),
            'numeros' => (new NumeroUserModel())->countAllResults(),
        ];

        $comptesOperateur = (new UserModel())
            ->select('users.id, users.nom, role.libelle AS role')
            ->join('role', 'role.id = users.id_role', 'left')
            ->where('users.id_role', 3)
            ->orderBy('users.nom', 'ASC')
            ->findAll();

        $comptesClients = (new UserModel())
            ->select('users.id, users.nom')
            ->where('users.id_role', 2)
            ->orderBy('users.nom', 'ASC')
            ->findAll();

        return [
            'prefixes' => $prefixes,
            'typesOperations' => $typesOperations,
            'recentOperations' => $recentOperations,
            'gainStats' => $gainStats,
            'accountStats' => $accountStats,
            'comptesOperateur' => $comptesOperateur,
            'comptesClients' => $comptesClients,
        ];
    }

    private function handlePostActions(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        $action = (string) $this->request->getPost('action');
        $redirect = null;

        if ($action === 'prefixe') {
            if ($this->validate([
                'prefixe' => 'required|min_length[3]|max_length[3]|is_unique[operateur.prefixe]',
                'operateur' => 'required|min_length[2]|max_length[50]',
            ])) {
                $prefixe = $this->normalizePrefixe($this->request->getPost('prefixe'));
                $operateur = trim((string) $this->request->getPost('operateur'));

                if (strlen($prefixe) !== 3) {
                    $redirect = redirect()->back()->withInput()->with('error', 'Le préfixe doit contenir exactement 3 chiffres.');
                } else {
                    $this->operateurModel->insert([
                        'prefixe' => $prefixe,
                        'operateur' => $operateur,
                    ]);

                    $redirect = redirect()->to('/operateur-office#prefixes')->with('success', 'Préfixe ajouté avec succès.');
                }
            } else {
                $redirect = redirect()->back()->withInput()->with('error', $this->getValidationError());
            }
        }

        if ($redirect === null && $action === 'type_operation') {
            if ($this->validate([
                'libelle' => 'required|min_length[2]|max_length[50]|is_unique[type_operation.libelle]',
            ])) {
                $libelle = trim((string) $this->request->getPost('libelle'));
                $this->typeOperationModel->insert([
                    'libelle' => $libelle,
                ]);

                $redirect = redirect()->to('/operateur-office#types')->with('success', 'Type d\'opération créé avec succès.');
            } else {
                $redirect = redirect()->back()->withInput()->with('error', $this->getValidationError());
            }
        }

        return $redirect;
    }

    /**
     * Afficher le tableau de bord opérateur
     */
    public function index()
    {
        if ($redirect = $this->guardOperateurSession()) {
            return $redirect;
        }

        if ($this->request->is('post') && ($redirect = $this->handlePostActions())) {
            return $redirect;
        }

        $userId = session()->get('user_id');
        $nom = session()->get('nom');
        $numero = session()->get('numero');

        $dashboardData = $this->getDashboardData();

        $stats = [
            'operateurs' => count($dashboardData['prefixes']),
            'comptes_operateur' => count($dashboardData['comptesOperateur']),
            'numeros' => $dashboardData['accountStats']['numeros'],
            'operations' => count($dashboardData['recentOperations']),
            'depots' => $this->historiqueOperationModel->where('id_operation', 1)->countAllResults(),
            'retraits' => $this->historiqueOperationModel->where('id_operation', 2)->countAllResults(),
            'transferts' => $this->historiqueOperationModel->where('id_operation', 3)->countAllResults(),
        ];

        $data = [
            'title' => 'Espace Opérateur',
            'nom' => $nom,
            'numero' => $numero,
            'user_id' => $userId,
            'stats' => $stats,
            'recentOperations' => $dashboardData['recentOperations'],
            'operateurs' => $dashboardData['prefixes'],
            'typesOperations' => $dashboardData['typesOperations'],
            'gainStats' => $dashboardData['gainStats'],
            'accountStats' => $dashboardData['accountStats'],
            'comptesOperateur' => $dashboardData['comptesOperateur'],
            'comptesClients' => $dashboardData['comptesClients'],
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
