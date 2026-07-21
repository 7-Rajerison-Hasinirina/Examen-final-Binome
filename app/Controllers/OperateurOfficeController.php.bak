<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\CommissionOperateurModel;
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
    protected $commissionOperateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
        $this->userModel = new UserModel();
        $this->numeroUserModel = new NumeroUserModel();
        $this->historiqueOperationModel = new HistoriqueOperationModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->commissionOperateurModel = new CommissionOperateurModel();
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

    private function getDashboardData(?int $userId = null): array
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

        $commissionRates = [];
        foreach ($prefixes as $prefix) {
            $commissionRates[$prefix['id']] = [
                'id' => null,
                'id_operateur' => $prefix['id'],
                'pourcentage' => 0.0,
                'operateur' => $prefix['operateur'],
                'prefixe' => $prefix['prefixe'],
            ];
        }

        $existingCommissions = $this->commissionOperateurModel->findAll();
        foreach ($existingCommissions as $commission) {
            if (isset($commissionRates[$commission['id_operateur']])) {
                $commissionRates[$commission['id_operateur']]['id'] = $commission['id'];
                $commissionRates[$commission['id_operateur']]['pourcentage'] = (float) $commission['pourcentage'];
            }
        }

        // Clients appartenant aux mêmes opérateurs que l'opérateur connecté
        $clientsOperateur = [];
        if ($userId !== null) {
            $myNums = $this->numeroUserModel->getNumerosByUser($userId);
            $prefixIds = array_values(array_unique(array_column($myNums, 'id_prefixe')));
            if (!empty($prefixIds)) {
                $operatorNames = $this->operateurModel
                    ->select('operateur')
                    ->whereIn('id', $prefixIds)
                    ->distinct()
                    ->findColumn('operateur');

                if (!empty($operatorNames)) {
                    $prefixIdsForOperator = $this->operateurModel
                        ->select('id')
                        ->whereIn('operateur', $operatorNames)
                        ->findColumn('id');

                    if (!empty($prefixIdsForOperator)) {
                        $clientsOperateur = $this->numeroUserModel
                            ->select('numero_user.numero, operateur.prefixe AS prefixe, users.nom AS client_nom, users.id AS client_id, numero_user.id_prefixe')
                            ->join('users', 'users.id = numero_user.id_user', 'left')
                            ->join('operateur', 'operateur.id = numero_user.id_prefixe', 'left')
                            ->whereIn('numero_user.id_prefixe', $prefixIdsForOperator)
                            ->where('users.id_role', 2)
                            ->orderBy('operateur.prefixe', 'ASC')
                            ->orderBy('numero_user.numero', 'ASC')
                            ->findAll();
                    }
                }
            }
        }
            // Grouper les résultats par client (regrouper les numéros par client)
            $clientsOperateurGrouped = [];
            if (!empty($clientsOperateur)) {
                foreach ($clientsOperateur as $row) {
                    $cid = $row['client_id'] ?? null;
                    if ($cid === null) continue;
                    if (!isset($clientsOperateurGrouped[$cid])) {
                        $clientsOperateurGrouped[$cid] = [
                            'client_id' => $cid,
                            'client_nom' => $row['client_nom'] ?? '-',
                            'numeros' => [],
                        ];
                    }
                    $prefixe = trim((string) ($row['prefixe'] ?? ''));
                    $numero = trim((string) ($row['numero'] ?? ''));
                    $full = $prefixe !== '' ? $prefixe . $numero : $numero;
                    $clientsOperateurGrouped[$cid]['numeros'][] = $full;
                }
                $clientsOperateurGrouped = array_values($clientsOperateurGrouped);
            }

        return [
            'prefixes' => $prefixes,
            'typesOperations' => $typesOperations,
            'recentOperations' => $recentOperations,
            'gainStats' => $gainStats,
            'accountStats' => $accountStats,
            'comptesOperateur' => $comptesOperateur,
            'comptesClients' => $comptesClients,
            'commissionRates' => array_values($commissionRates),
            'clientsOperateur' => $clientsOperateur,
            'clientsOperateurGrouped' => $clientsOperateurGrouped,
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
                    $redirect = redirect()->to('/operateur-office#prefixes')->withInput()->with('error', 'Le préfixe doit contenir exactement 3 chiffres.');
                } else {
                    $this->operateurModel->insert([
                        'prefixe' => $prefixe,
                        'operateur' => $operateur,
                    ]);

                    $redirect = redirect()->to('/operateur-office#prefixes')->with('success', 'Préfixe ajouté avec succès.');
                }
            } else {
                $redirect = redirect()->to('/operateur-office#prefixes')->withInput()->with('error', $this->getValidationError());
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
                $redirect = redirect()->to('/operateur-office#types')->withInput()->with('error', $this->getValidationError());
            }
        }

        if ($redirect === null && $action === 'commission') {
            $commissionId = $this->request->getPost('commission_id');
            $operateurId = (int) $this->request->getPost('id_operateur');
            $pourcentage = (float) $this->request->getPost('pourcentage');

            if ($operateurId <= 0 || $pourcentage < 0) {
                $redirect = redirect()->to('/operateur-office#commissions')->withInput()->with('error', 'Données de commission invalides.');
            } else {
                $data = [
                    'id_operateur' => $operateurId,
                    'pourcentage' => $pourcentage,
                ];

                if (!empty($commissionId)) {
                    $data['id'] = (int) $commissionId;
                }

                $this->commissionOperateurModel->save($data);
                $redirect = redirect()->to('/operateur-office#commissions')->with('success', 'Commission mise à jour avec succès.');
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

        $dashboardData = $this->getDashboardData($userId);

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
            'clientsOperateur' => $dashboardData['clientsOperateur'],
            'clientsOperateurGrouped' => $dashboardData['clientsOperateurGrouped'],
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
