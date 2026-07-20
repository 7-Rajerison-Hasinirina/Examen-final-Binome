<?php

namespace App\Controllers;

use App\Models\HistoriqueOperationModel;
use App\Models\NumeroUserModel;
use App\Models\TypeOperationModel;
use CodeIgniter\HTTP\RedirectResponse;

class ClientOfficeController extends BaseController
{
    protected $numeroUserModel;
    protected $typeOperationModel;
    protected $historiqueOperationModel;

    public function __construct()
    {
        $this->numeroUserModel = new NumeroUserModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->historiqueOperationModel = new HistoriqueOperationModel();
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
            'numero_destination' => 'required',
            'montant' => 'required|numeric|greater_than[0]',
            'reference' => 'permit_empty|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->getValidationError());
        }

        $userId = $this->getCurrentUserId();
        $numeroSource = $this->normalizeNumero($this->request->getPost('numero_source'));
        $numeroDestination = $this->normalizeNumero($this->request->getPost('numero_destination'));
        $montant = (float) $this->request->getPost('montant');
        $reference = trim((string) $this->request->getPost('reference'));

        $source = $this->numeroUserModel->findByNumeroAndUser($numeroSource, $userId);
        if (!$source) {
            return redirect()->back()->withInput()->with('error', 'Le numéro source ne vous appartient pas.');
        }

        $destination = $this->numeroUserModel->findByNumero($numeroDestination);
        if (!$destination) {
            return redirect()->back()->withInput()->with('error', 'Le numéro destinataire est introuvable.');
        }

        if ($source['numero'] === $destination['numero']) {
            return redirect()->back()->withInput()->with('error', 'Le numéro source et le numéro destinataire doivent être différents.');
        }

        $soldeActuel = $this->historiqueOperationModel->getBalanceByNumero($numeroSource);
        if ($montant > $soldeActuel) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour effectuer ce transfert.');
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueOperationModel->insert([
            'id_user' => $userId,
            'id_operation' => $this->getOperationId('Transfert'),
            'valeur' => $montant,
            'numero_source' => $source['numero'],
            'numero_destination' => $destination['numero'],
            'reference' => $reference !== '' ? $reference : 'Transfert sortant',
            'sens' => 'sortie',
        ]);

        $this->historiqueOperationModel->insert([
            'id_user' => (int) $destination['id_user'],
            'id_operation' => $this->getOperationId('Transfert'),
            'valeur' => $montant,
            'numero_source' => $source['numero'],
            'numero_destination' => $destination['numero'],
            'reference' => $reference !== '' ? $reference : 'Transfert entrant',
            'sens' => 'entree',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Le transfert a échoué.');
        }

        session()->setFlashdata('success', 'Transfert de ' . $montant . ' Ar effectué avec succès.');
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
