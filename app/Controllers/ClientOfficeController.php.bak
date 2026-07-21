<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\CommissionOperateurModel;
use App\Models\HistoriqueOperationModel;
use App\Models\NumeroUserModel;
use App\Models\TypeOperationModel;
use CodeIgniter\HTTP\RedirectResponse;

class ClientOfficeController extends BaseController
{
    protected $numeroUserModel;
    protected $typeOperationModel;
    protected $historiqueOperationModel;
    protected $baremeFraisModel;
    protected $commissionOperateurModel;

    public function __construct()
    {
        $this->numeroUserModel = new NumeroUserModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->historiqueOperationModel = new HistoriqueOperationModel();
        $this->baremeFraisModel = new BaremeFraisModel();
        $this->commissionOperateurModel = new CommissionOperateurModel();
    }

    private function guardClientSession(): ?RedirectResponse
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/');
        }

        return null;
    }

    private function getCurrentUserId(): int
    {
        return (int) session()->get('user_id');
    }

    private function getOperationId(string $libelle): int
    {
        $operationId = $this->typeOperationModel->getOperationIdByLibelle($libelle);

        if ($operationId === null) {
            throw new \RuntimeException('Type d\'opération introuvable : ' . $libelle);
        }

        return $operationId;
    }

    private function normalizeNumero(?string $numero): string
    {
        return preg_replace('/\D+/', '', (string) $numero) ?? '';
    }

    private function getValidationError(): string
    {
        $errors = $this->validator?->getErrors() ?? [];

        return $errors ? implode(' ', $errors) : 'Données invalides.';
    }

    private function getNumerosAvecSolde(int $userId): array
    {
        return $this->historiqueOperationModel->getBalancesByNumbers(
            $this->numeroUserModel->getNumerosByUser($userId)
        );
    }

    private function getNumeroAvecOperateur(string $numero): ?array
    {
        return $this->numeroUserModel->findByNumeroWithOperateur($numero);
    }

    private function getNumeroAvecOperateurUtilisateur(string $numero, int $userId): ?array
    {
        return $this->numeroUserModel->findByNumeroAndUserWithOperateur($numero, $userId);
    }

    private function getCommissionWithdrawalFee(array $destination, float $montant): float
    {
        $commission = $this->commissionOperateurModel->getByOperateur((int) ($destination['id_prefixe'] ?? 0));
        if (!$commission) {
            return 0.0;
        }

        return ((float) $commission['pourcentage'] * $montant) / 100.0;
    }

    private function calculateTransfertAmounts(array $source, array $destination, float $montant, bool $includeWithdrawalFee): array
    {
        $transferFee = $this->baremeFraisModel->getFraisForOperation(
            (int) ($source['id_prefixe'] ?? 0),
            $this->getOperationId('Transfert'),
            $montant
        );

        $withdrawalFee = 0.0;
        if ($includeWithdrawalFee && strcasecmp(trim((string) ($source['operateur'] ?? '')), trim((string) ($destination['operateur'] ?? ''))) !== 0) {
            $withdrawalFee = $this->getCommissionWithdrawalFee($destination, $montant);
        }

        return [
            'transferFee' => $transferFee,
            'withdrawalFee' => $withdrawalFee,
            'totalDebit' => $montant + $transferFee + $withdrawalFee,
        ];
    }

    private function getNumeroActif(array $numeros): ?array
    {
        if (empty($numeros)) {
            session()->remove('client_active_numero_id');
            return null;
        }

        $activeNumeroId = (int) session()->get('client_active_numero_id');
        foreach ($numeros as $numero) {
            if ((int) $numero['id'] === $activeNumeroId) {
                return $numero;
            }
        }

        $numeroActif = $numeros[0];
        session()->set('client_active_numero_id', (int) $numeroActif['id']);

        return $numeroActif;
    }

    private function getClientContext(int $userId): array
    {
        $numeros = $this->getNumerosAvecSolde($userId);
        $numeroActif = $this->getNumeroActif($numeros);

        return [
            'numeros' => $numeros,
            'numeroActif' => $numeroActif,
            'soldeActif' => $numeroActif['solde'] ?? 0,
        ];
    }

    /**
     * Afficher le tableau de bord client
     */
    public function index()
    {
        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        $userId = $this->getCurrentUserId();
        $context = $this->getClientContext($userId);

        $data = [
            'title' => 'Espace Client',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $context['numeros'],
            'numeroActif' => $context['numeroActif'],
            'soldeActif' => $context['soldeActif'],
            'operations' => $this->typeOperationModel->getAllOperations(),
        ];

        return view('ClientOffice', $data);
    }

    /**
     * Afficher le solde
     */
    public function solde()
    {
        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        $userId = $this->getCurrentUserId();
        $context = $this->getClientContext($userId);

        $data = [
            'title' => 'Voir le Solde',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $context['numeros'],
            'numeroActif' => $context['numeroActif'],
            'soldeActif' => $context['soldeActif'],
        ];

        return view('client/solde', $data);
    }

    /**
     * Afficher le formulaire de retrait
     */
    public function retrait()
    {
        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        $userId = $this->getCurrentUserId();
        $context = $this->getClientContext($userId);

        $data = [
            'title' => 'Faire un Retrait',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $context['numeros'],
            'numeroActif' => $context['numeroActif'],
            'soldeActif' => $context['soldeActif'],
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

        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        if (!$this->validate([
            'numero' => 'required',
            'montant' => 'required|numeric|greater_than[0]',
            'raison' => 'permit_empty|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->getValidationError());
        }

        $userId = $this->getCurrentUserId();
        $numeroRetrait = $this->normalizeNumero($this->request->getPost('numero'));
        $montant = (float) $this->request->getPost('montant');
        $raison = trim((string) $this->request->getPost('raison'));

        $numero = $this->numeroUserModel->findByNumeroAndUser($numeroRetrait, $userId);
        if (!$numero) {
            return redirect()->back()->withInput()->with('error', 'Le numéro sélectionné ne vous appartient pas.');
        }

        $soldeActuel = $this->historiqueOperationModel->getBalanceByNumero($numeroRetrait);
        if ($montant > $soldeActuel) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour effectuer ce retrait.');
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueOperationModel->insert([
            'id_user' => $userId,
            'id_operation' => $this->getOperationId('Retrait'),
            'valeur' => $montant,
            'numero_source' => $numero['numero'],
            'reference' => $raison !== '' ? $raison : 'Retrait client',
            'sens' => 'sortie',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Le retrait a échoué.');
        }

        session()->setFlashdata('success', 'Retrait de ' . $montant . ' Ar effectué avec succès.');
        return redirect()->to('/client-office');
    }

    /**
     * Afficher le formulaire de dépôt
     */
    public function depot()
    {
        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        $userId = $this->getCurrentUserId();
        $context = $this->getClientContext($userId);

        $data = [
            'title' => 'Faire un Dépôt',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $context['numeros'],
            'numeroActif' => $context['numeroActif'],
            'soldeActif' => $context['soldeActif'],
        ];

        return view('client/depot', $data);
    }

    /**
     * Traiter le dépôt
     */
    public function traiterDepot()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        if (!$this->validate([
            'numero' => 'required',
            'montant' => 'required|numeric|greater_than[0]',
            'reference' => 'permit_empty|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->getValidationError());
        }

        $userId = $this->getCurrentUserId();
        $numeroDepot = $this->normalizeNumero($this->request->getPost('numero'));
        $montant = (float) $this->request->getPost('montant');
        $reference = trim((string) $this->request->getPost('reference'));

        $numero = $this->numeroUserModel->findByNumeroAndUser($numeroDepot, $userId);
        if (!$numero) {
            return redirect()->back()->withInput()->with('error', 'Le numéro sélectionné ne vous appartient pas.');
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueOperationModel->insert([
            'id_user' => $userId,
            'id_operation' => $this->getOperationId('Depot'),
            'valeur' => $montant,
            'numero_destination' => $numero['numero'],
            'reference' => $reference !== '' ? $reference : 'Dépôt client',
            'sens' => 'entree',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Le dépôt a échoué.');
        }

        session()->setFlashdata('success', 'Dépôt de ' . $montant . ' Ar effectué avec succès.');
        return redirect()->to('/client-office');
    }

    /**
     * Afficher le formulaire de transfert
     */
    public function transfert()
    {
        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        $userId = $this->getCurrentUserId();
        $context = $this->getClientContext($userId);

        $data = [
            'title' => 'Faire un Transfert',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $context['numeros'],
            'numeroActif' => $context['numeroActif'],
            'soldeActif' => $context['soldeActif'],
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

        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        if (!$this->validate([
            'numero_source' => 'required',
            'destinations.*' => 'required',
            'montants.*' => 'required|numeric|greater_than[0]',
            'reference' => 'permit_empty|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->getValidationError());
        }

        $userId = $this->getCurrentUserId();
        $numeroSource = $this->normalizeNumero($this->request->getPost('numero_source'));
        $destinations = $this->request->getPost('destinations');
        $montants = $this->request->getPost('montants');
        $reference = trim((string) $this->request->getPost('reference'));
        $includeWithdrawalFee = $this->request->getPost('include_withdrawal_fee') === '1';

        if (empty($destinations) || !is_array($destinations) || empty($montants) || !is_array($montants)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir au moins un numéro de destination et un montant.');
        }

        $source = $this->getNumeroAvecOperateurUtilisateur($numeroSource, $userId);
        if (!$source) {
            return redirect()->back()->withInput()->with('error', 'Le numéro source ne vous appartient pas.');
        }

        $transfers = [];
        foreach ($destinations as $index => $rawDestination) {
            $destinationNumero = $this->normalizeNumero($rawDestination);
            $montant = isset($montants[$index]) ? (float) $montants[$index] : 0;

            if ($destinationNumero === '' || $montant <= 0) {
                continue;
            }

            if ($destinationNumero === $source['numero']) {
                return redirect()->back()->withInput()->with('error', 'Le numéro source et le numéro destinataire doivent être différents.');
            }

            $transfers[] = [
                'destination' => $destinationNumero,
                'montant' => $montant,
            ];
        }

        if (empty($transfers)) {
            return redirect()->back()->withInput()->with('error', 'Aucun transfert valide n’a été saisi.');
        }

        $firstDestination = $transfers[0]['destination'];
        $firstDestinationData = $this->getNumeroAvecOperateur($firstDestination);
        if (!$firstDestinationData) {
            return redirect()->back()->withInput()->with('error', 'Le numéro destinataire est introuvable.');
        }

        $destinationOperatorId = (int) ($firstDestinationData['id_prefixe'] ?? 0);
        foreach ($transfers as $transfer) {
            $destinationData = $this->getNumeroAvecOperateur($transfer['destination']);
            if (!$destinationData || (int) ($destinationData['id_prefixe'] ?? 0) !== $destinationOperatorId) {
                return redirect()->back()->withInput()->with('error', 'Les transferts multiples ne sont autorisés que vers des numéros du même opérateur.');
            }
        }

        $soldeActuel = $this->historiqueOperationModel->getBalanceByNumero($numeroSource);
        $totalDebit = 0;
        $transferDetails = [];

        foreach ($transfers as $transfer) {
            $destinationData = $this->getNumeroAvecOperateur($transfer['destination']);
            $fees = $this->calculateTransfertAmounts($source, $destinationData, $transfer['montant'], $includeWithdrawalFee);
            $totalDebit += $fees['totalDebit'];
            $transferDetails[] = [
                'destinationData' => $destinationData,
                'montant' => $transfer['montant'],
                'fees' => $fees,
            ];
        }

        if ($totalDebit > $soldeActuel) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour couvrir le montant total et les frais.');
        }

        $db = db_connect();
        $db->transStart();

        foreach ($transferDetails as $detail) {
            $destinationData = $detail['destinationData'];
            $montant = $detail['montant'];
            $fees = $detail['fees'];

            $this->historiqueOperationModel->insert([
                'id_user' => $userId,
                'id_operation' => $this->getOperationId('Transfert'),
                'valeur' => $fees['totalDebit'],
                'numero_source' => $source['numero'],
                'numero_destination' => $destinationData['numero'],
                'reference' => $reference !== '' ? $reference : 'Transfert sortant',
                'sens' => 'sortie',
            ]);

            $this->historiqueOperationModel->insert([
                'id_user' => (int) $destinationData['id_user'],
                'id_operation' => $this->getOperationId('Transfert'),
                'valeur' => $montant,
                'numero_source' => $source['numero'],
                'numero_destination' => $destinationData['numero'],
                'reference' => $reference !== '' ? $reference : 'Transfert entrant',
                'sens' => 'entree',
            ]);

            if ($fees['transferFee'] > 0 || $fees['withdrawalFee'] > 0) {
                $this->historiqueOperationModel->insert([
                    'id_user' => $userId,
                    'id_operation' => $this->getOperationId('Transfert'),
                    'valeur' => $fees['transferFee'] + $fees['withdrawalFee'],
                    'numero_source' => $source['numero'],
                    'numero_destination' => $destinationData['numero'],
                    'reference' => 'Frais de transfert: ' . number_format($fees['transferFee'], 2, ',', ' ') . ' Ar, frais de retrait: ' . number_format($fees['withdrawalFee'], 2, ',', ' ') . ' Ar',
                    'sens' => 'sortie',
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Le transfert a échoué.');
        }

        session()->setFlashdata('success', 'Transfert multiple effectué avec succès. Montant total débité : ' . number_format($totalDebit, 2, ',', ' ') . ' Ar.');
        return redirect()->to('/client-office');
    }

    /**
     * Afficher l'historique
     */
    public function historique()
    {
        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        $userId = $this->getCurrentUserId();
        $context = $this->getClientContext($userId);

        $data = [
            'title' => 'Historique des Opérations',
            'nom' => session()->get('nom'),
            'user_id' => $userId,
            'numeros' => $context['numeros'],
            'numeroActif' => $context['numeroActif'],
            'soldeActif' => $context['soldeActif'],
            'operations' => $context['numeroActif']
                ? $this->historiqueOperationModel->getOperationsByNumero((string) $context['numeroActif']['numero'])
                : [],
        ];

        return view('client/historique', $data);
    }

    public function activerNumero()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        if ($redirect = $this->guardClientSession()) {
            return $redirect;
        }

        $userId = $this->getCurrentUserId();
        $numeroId = (int) $this->request->getPost('numero_id');
        $numeros = $this->numeroUserModel->getNumerosByUser($userId);

        foreach ($numeros as $numero) {
            if ((int) $numero['id'] === $numeroId) {
                session()->set('client_active_numero_id', $numeroId);
                session()->setFlashdata('success', 'Numéro actif mis à jour.');
                return redirect()->back();
            }
        }

        return redirect()->back()->with('error', 'Numéro invalide ou non autorisé.');
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
