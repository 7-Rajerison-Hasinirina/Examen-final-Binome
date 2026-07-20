<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use App\Models\ObjectifModel;
use App\Models\ActiviteModel;
use App\Models\CodeModel;
use App\Models\CodeUserModel;
use App\Models\NormeImcModel;
use App\Models\TarifDureeModel;

class BackOffice extends BaseController
{
    private function baseData(string $title): array
    {
        // Prepare common view data for back-office pages.
        $user = session()->get('user');

        return [
            'title' => $title,
            'userName' => $user['nom'] ?? 'Admin',
        ];
    }

    public function dashboard()
    {
        // Dashboard stats view.
        $data = $this->baseData('Back office - Dashboard');
        $data = array_merge($data, $this->getDashboardData());

        return view('back_office/dashboard', $data);
    }

    public function exportDashboardCsv()
    {
        $dashboard = $this->getDashboardData();
        $stats = $dashboard['stats'];
        $objectifStats = $dashboard['objectifStats'];
        $selectionStats = $dashboard['selectionStats'];

        $rows = [];
        $rows[] = ['metric', 'value'];
        foreach ($stats as $label => $value) {
            $rows[] = [$label, (string) $value];
        }

        $rows[] = [];
        $rows[] = ['objectif', 'regimes_count', 'activites_count'];
        foreach ($objectifStats as $row) {
            $rows[] = [
                $row['libelle'],
                (string) $row['regimes_count'],
                (string) $row['activites_count'],
            ];
        }

        $rows[] = [];
        $rows[] = ['objectif_selection', 'total'];
        foreach ($selectionStats as $row) {
            $rows[] = [
                $row['objectif'],
                (string) $row['total'],
            ];
        }

        $output = '';
            foreach ($rows as $row) {
                $output .= implode(';', array_map(static function ($value) {
                    $escaped = str_replace('"', '""', (string) $value);
                    return '"' . $escaped . '"';
                }, $row)) . "\n";
        }

        $filename = 'dashboard_stats_' . date('Ymd') . '.csv';

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($output);
    }

    public function exportDashboardPdf()
    {
        $dashboard = $this->getDashboardData();
        $stats = $dashboard['stats'];
        $objectifStats = $dashboard['objectifStats'];
        $selectionStats = $dashboard['selectionStats'];

        require_once APPPATH . 'ThirdParty/fpdf186/fpdf.php';

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Dashboard stats', 0, 1);

        $pdf->SetFont('Arial', '', 11);
        foreach ($stats as $label => $value) {
            $pdf->Cell(70, 8, $label, 0, 0);
            $pdf->Cell(0, 8, (string) $value, 0, 1);
        }

        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Repartition par objectif', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        foreach ($objectifStats as $row) {
            $line = sprintf('%s - Regimes: %s | Activites: %s', $row['libelle'], $row['regimes_count'], $row['activites_count']);
            $pdf->Cell(0, 7, $line, 0, 1);
        }

        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Selections par objectif', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        foreach ($selectionStats as $row) {
            $line = sprintf('%s: %s', $row['objectif'], $row['total']);
            $pdf->Cell(0, 7, $line, 0, 1);
        }

        $filename = 'dashboard_stats_' . date('Ymd') . '.pdf';
        $content = $pdf->Output('S');

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($content);
    }

    public function regimes()
    {
        // Liste des regimes avec filtres.
        helper('form');

        $regimeModel = new RegimeModel();
        $objectifModel = new ObjectifModel();

        $query = $regimeModel
            ->select('regime.*, objectif.libelle as objectif_libelle')
            ->join('objectif', 'objectif.id_objectif = regime.id_objectif', 'left');

        $filters = [
            'id_objectif' => $this->request->getGet('id_objectif'),
            'variation_min' => $this->request->getGet('variation_min'),
            'variation_max' => $this->request->getGet('variation_max'),
            'prix_min' => $this->request->getGet('prix_min'),
            'prix_max' => $this->request->getGet('prix_max'),
            'duree_min' => $this->request->getGet('duree_min'),
            'duree_max' => $this->request->getGet('duree_max'),
        ];

        if (is_numeric($filters['id_objectif'])) {
            $query->where('regime.id_objectif', (int) $filters['id_objectif']);
        }
        if ($filters['variation_min'] !== null && $filters['variation_min'] !== '') {
            $query->where('regime.variation_poids >=', (float) $filters['variation_min']);
        }
        if ($filters['variation_max'] !== null && $filters['variation_max'] !== '') {
            $query->where('regime.variation_poids <=', (float) $filters['variation_max']);
        }
        if ($filters['prix_min'] !== null && $filters['prix_min'] !== '') {
            $query->where('regime.prix >=', (float) $filters['prix_min']);
        }
        if ($filters['prix_max'] !== null && $filters['prix_max'] !== '') {
            $query->where('regime.prix <=', (float) $filters['prix_max']);
        }
        if ($filters['duree_min'] !== null && $filters['duree_min'] !== '') {
            $query->where('regime.duree_jours >=', (int) $filters['duree_min']);
        }
        if ($filters['duree_max'] !== null && $filters['duree_max'] !== '') {
            $query->where('regime.duree_jours <=', (int) $filters['duree_max']);
        }

        $data = $this->baseData('Back office - Regimes');
        $regimes = $query->orderBy('regime.id_regime', 'DESC')->findAll();
        $data['regimes'] = $regimeModel->appliquerTarifDureeListe($regimes);
        $data['objectifs'] = $objectifModel->orderBy('libelle', 'ASC')->findAll();
        $data['filters'] = $filters;
        $data['errors'] = session()->getFlashdata('errors') ?? [];

        return view('back_office/regimes', $data);
    }

    public function createRegime()
    {
        // Formulaire creation regime.
        helper('form');

        $objectifModel = new ObjectifModel();

        $data = $this->baseData('Back office - Nouveau regime');
        $data['objectifs'] = $objectifModel->orderBy('libelle', 'ASC')->findAll();
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = false;
        $data['regime'] = null;

        return view('back_office/regimes_form', $data);
    }

    public function storeRegime()
    {
        // Enregistre un regime.
        $regimeModel = new RegimeModel();

        $payload = $this->request->getPost();
        $validationError = $this->validateRegimePercentages($payload);

        if ($validationError !== null) {
            return redirect()->back()->withInput()->with('errors', [$validationError]);
        }

        if (!$regimeModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $regimeModel->errors());
        }

        $regimeModel->insert($payload);

        return redirect()->to('/back-office/regimes')->with('success', 'Regime cree.');
    }

    public function editRegime(int $id)
    {
        // Formulaire edition regime.
        helper('form');

        $regimeModel = new RegimeModel();
        $objectifModel = new ObjectifModel();

        $regime = $regimeModel->find($id);
        if (!$regime) {
            return redirect()->to('/back-office/regimes')->with('error', 'Regime introuvable.');
        }

        $data = $this->baseData('Back office - Modifier regime');
        $data['objectifs'] = $objectifModel->orderBy('libelle', 'ASC')->findAll();
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = true;
        $data['regime'] = $regime;

        return view('back_office/regimes_form', $data);
    }

    public function updateRegime(int $id)
    {
        // Met a jour un regime.
        $regimeModel = new RegimeModel();
        $regime = $regimeModel->find($id);

        if (!$regime) {
            return redirect()->to('/back-office/regimes')->with('error', 'Regime introuvable.');
        }

        $payload = $this->request->getPost();
        $validationError = $this->validateRegimePercentages($payload);

        if ($validationError !== null) {
            return redirect()->back()->withInput()->with('errors', [$validationError]);
        }

        if (!$regimeModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $regimeModel->errors());
        }

        $regimeModel->update($id, $payload);

        return redirect()->to('/back-office/regimes')->with('success', 'Regime mis a jour.');
    }

    public function deleteRegime(int $id)
    {
        // Supprime un regime si non utilise.
        $regimeModel = new RegimeModel();
        $regime = $regimeModel->find($id);

        if (!$regime) {
            return redirect()->to('/back-office/regimes')->with('error', 'Regime introuvable.');
        }

        $db = \Config\Database::connect();
        $hasUsage = $db->table('regime_user')->where('id_regime', $id)->countAllResults() > 0;
        if ($hasUsage) {
            return redirect()->to('/back-office/regimes')->with('error', 'Suppression impossible: regime deja utilise.');
        }

        $regimeModel->delete($id);

        return redirect()->to('/back-office/regimes')->with('success', 'Regime supprime.');
    }

    private function validateRegimePercentages(array $payload): ?string
    {
        // Verifie la somme des pourcentages.
        $viande = $payload['viande'] ?? null;
        $poisson = $payload['poisson'] ?? null;
        $volaille = $payload['volaille'] ?? null;

        if ($viande === null || $poisson === null || $volaille === null) {
            return 'Les pourcentages viande, poisson, volaille sont requis.';
        }

        if (!is_numeric($viande) || !is_numeric($poisson) || !is_numeric($volaille)) {
            return 'Les pourcentages doivent etre numeriques.';
        }

        if ((float) $viande < 0 || (float) $poisson < 0 || (float) $volaille < 0) {
            return 'Les pourcentages doivent etre superieurs ou egaux a 0.';
        }

        $sum = (float) $viande + (float) $poisson + (float) $volaille;
        if (abs($sum - 100.0) > 0.01) {
            return 'La somme viande + poisson + volaille doit faire 100.';
        }

        return null;
    }

    public function activites()
    {
        // Liste des activites avec filtres.
        helper('form');

        $activiteModel = new ActiviteModel();
        $sportModel = model('App\\Models\\SportModel');
        $objectifModel = new ObjectifModel();
        $niveauModel = model('App\\Models\\NiveauIntensiteModel');

        $query = $activiteModel
            ->select('activite_sportive.*, sport.libelle as sport_libelle, objectif.libelle as objectif_libelle, niveau_intensite.libelle as niveau_libelle')
            ->join('sport', 'sport.id_sport = activite_sportive.id_sport', 'left')
            ->join('objectif', 'objectif.id_objectif = activite_sportive.id_objectif', 'left')
            ->join('niveau_intensite', 'niveau_intensite.id_niveau = activite_sportive.id_niveau', 'left');

        $filters = [
            'id_sport' => $this->request->getGet('id_sport'),
            'id_objectif' => $this->request->getGet('id_objectif'),
            'id_niveau' => $this->request->getGet('id_niveau'),
            'variation_min' => $this->request->getGet('variation_min'),
            'variation_max' => $this->request->getGet('variation_max'),
        ];

        if (is_numeric($filters['id_sport'])) {
            $query->where('activite_sportive.id_sport', (int) $filters['id_sport']);
        }
        if (is_numeric($filters['id_objectif'])) {
            $query->where('activite_sportive.id_objectif', (int) $filters['id_objectif']);
        }
        if (is_numeric($filters['id_niveau'])) {
            $query->where('activite_sportive.id_niveau', (int) $filters['id_niveau']);
        }
        if ($filters['variation_min'] !== null && $filters['variation_min'] !== '') {
            $query->where('activite_sportive.variation_poids >=', (float) $filters['variation_min']);
        }
        if ($filters['variation_max'] !== null && $filters['variation_max'] !== '') {
            $query->where('activite_sportive.variation_poids <=', (float) $filters['variation_max']);
        }

        $data = $this->baseData('Back office - Activites');
        $data['activites'] = $query->orderBy('activite_sportive.id_activite', 'DESC')->findAll();
        $data['sports'] = $sportModel->orderBy('libelle', 'ASC')->findAll();
        $data['objectifs'] = $objectifModel->orderBy('libelle', 'ASC')->findAll();
        $data['niveaux'] = $niveauModel->orderBy('libelle', 'ASC')->findAll();
        $data['filters'] = $filters;
        $data['errors'] = session()->getFlashdata('errors') ?? [];

        return view('back_office/activites', $data);
    }

    public function createActivite()
    {
        // Formulaire creation activite.
        helper('form');

        $sportModel = model('App\\Models\\SportModel');
        $objectifModel = new ObjectifModel();
        $niveauModel = model('App\\Models\\NiveauIntensiteModel');

        $data = $this->baseData('Back office - Nouvelle activite');
        $data['sports'] = $sportModel->orderBy('libelle', 'ASC')->findAll();
        $data['objectifs'] = $objectifModel->orderBy('libelle', 'ASC')->findAll();
        $data['niveaux'] = $niveauModel->orderBy('libelle', 'ASC')->findAll();
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = false;
        $data['activite'] = null;

        return view('back_office/activites_form', $data);
    }

    public function storeActivite()
    {
        // Enregistre une activite.
        $activiteModel = new ActiviteModel();

        $payload = $this->request->getPost();
        if (!$activiteModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $activiteModel->errors());
        }

        $activiteModel->insert($payload);

        return redirect()->to('/back-office/activites')->with('success', 'Activite creee.');
    }

    public function editActivite(int $id)
    {
        // Formulaire edition activite.
        helper('form');

        $activiteModel = new ActiviteModel();
        $sportModel = model('App\\Models\\SportModel');
        $objectifModel = new ObjectifModel();
        $niveauModel = model('App\\Models\\NiveauIntensiteModel');

        $activite = $activiteModel->find($id);
        if (!$activite) {
            return redirect()->to('/back-office/activites')->with('error', 'Activite introuvable.');
        }

        $data = $this->baseData('Back office - Modifier activite');
        $data['sports'] = $sportModel->orderBy('libelle', 'ASC')->findAll();
        $data['objectifs'] = $objectifModel->orderBy('libelle', 'ASC')->findAll();
        $data['niveaux'] = $niveauModel->orderBy('libelle', 'ASC')->findAll();
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = true;
        $data['activite'] = $activite;

        return view('back_office/activites_form', $data);
    }

    public function updateActivite(int $id)
    {
        // Met a jour une activite.
        $activiteModel = new ActiviteModel();
        $activite = $activiteModel->find($id);

        if (!$activite) {
            return redirect()->to('/back-office/activites')->with('error', 'Activite introuvable.');
        }

        $payload = $this->request->getPost();
        if (!$activiteModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $activiteModel->errors());
        }

        $activiteModel->update($id, $payload);

        return redirect()->to('/back-office/activites')->with('success', 'Activite mise a jour.');
    }

    public function deleteActivite(int $id)
    {
        // Supprime une activite.
        $activiteModel = new ActiviteModel();
        $activite = $activiteModel->find($id);

        if (!$activite) {
            return redirect()->to('/back-office/activites')->with('error', 'Activite introuvable.');
        }

        $activiteModel->delete($id);

        return redirect()->to('/back-office/activites')->with('success', 'Activite supprimee.');
    }

    public function codes()
    {
        // Liste des codes avec filtres.
        helper('form');

        $codeModel = new CodeModel();
        $codeUserModel = new CodeUserModel();
        $today = date('Y-m-d');

        $filters = [
            'libelle' => $this->request->getGet('libelle'),
            'montant_min' => $this->request->getGet('montant_min'),
            'montant_max' => $this->request->getGet('montant_max'),
            'date_min' => $this->request->getGet('date_min'),
            'date_max' => $this->request->getGet('date_max'),
            'etat' => $this->request->getGet('etat'),
        ];

        $query = $codeModel->select('code.*');
        if (!empty($filters['libelle'])) {
            $query->like('code.libelle', (string) $filters['libelle']);
        }
        if ($filters['montant_min'] !== null && $filters['montant_min'] !== '') {
            $query->where('code.montant >=', (float) $filters['montant_min']);
        }
        if ($filters['montant_max'] !== null && $filters['montant_max'] !== '') {
            $query->where('code.montant <=', (float) $filters['montant_max']);
        }
        if (!empty($filters['date_min'])) {
            $query->where('code.date_expiration >=', $filters['date_min']);
        }
        if (!empty($filters['date_max'])) {
            $query->where('code.date_expiration <=', $filters['date_max']);
        }
        if ($filters['etat'] === 'valide') {
            $query->where('code.date_expiration >=', $today);
        } elseif ($filters['etat'] === 'expire') {
            $query->where('code.date_expiration <', $today);
        }

        $codes = $query->orderBy('code.id_code', 'DESC')->findAll();
        $usageByCode = [];
        if (!empty($codes)) {
            $ids = array_column($codes, 'id_code');
            $rows = $codeUserModel
                ->select('id_code, COUNT(*) as total')
                ->whereIn('id_code', $ids)
                ->groupBy('id_code')
                ->findAll();

            foreach ($rows as $row) {
                $usageByCode[(int) $row['id_code']] = (int) $row['total'];
            }
        }

        $data = $this->baseData('Back office - Codes');
        $data['codes'] = $codes;
        $data['filters'] = $filters;
        $data['usageByCode'] = $usageByCode;
        $data['today'] = $today;
        $data['errors'] = session()->getFlashdata('errors') ?? [];

        return view('back_office/codes', $data);
    }

    public function createCode()
    {
        // Formulaire creation code.
        helper('form');

        $data = $this->baseData('Back office - Nouveau code');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = false;
        $data['code'] = null;

        return view('back_office/codes_form', $data);
    }

    public function storeCode()
    {
        // Enregistre un code.
        $codeModel = new CodeModel();
        $payload = $this->request->getPost();

        if (!$codeModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $codeModel->errors());
        }

        $codeModel->insert($payload);

        return redirect()->to('/back-office/codes')->with('success', 'Code cree.');
    }

    public function editCode(int $id)
    {
        // Formulaire edition code.
        helper('form');

        $codeModel = new CodeModel();
        $code = $codeModel->find($id);

        if (!$code) {
            return redirect()->to('/back-office/codes')->with('error', 'Code introuvable.');
        }

        $data = $this->baseData('Back office - Modifier code');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = true;
        $data['code'] = $code;

        return view('back_office/codes_form', $data);
    }

    public function updateCode(int $id)
    {
        // Met a jour un code.
        $codeModel = new CodeModel();
        $code = $codeModel->find($id);

        if (!$code) {
            return redirect()->to('/back-office/codes')->with('error', 'Code introuvable.');
        }

        $payload = $this->request->getPost();
        $payload['id_code'] = $id;

        if (!$codeModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $codeModel->errors());
        }

        $codeModel->update($id, $payload);

        return redirect()->to('/back-office/codes')->with('success', 'Code mis a jour.');
    }

    public function deleteCode(int $id)
    {
        // Supprime un code si non utilise.
        $codeModel = new CodeModel();
        $codeUserModel = new CodeUserModel();
        $code = $codeModel->find($id);

        if (!$code) {
            return redirect()->to('/back-office/codes')->with('error', 'Code introuvable.');
        }

        $hasUsage = $codeUserModel->where('id_code', $id)->countAllResults() > 0;
        if ($hasUsage) {
            return redirect()->to('/back-office/codes')->with('error', 'Suppression impossible: code deja utilise.');
        }

        $codeModel->delete($id);

        return redirect()->to('/back-office/codes')->with('success', 'Code supprime.');
    }

    public function parametres()
    {
        // Liste des parametres (objectifs, normes IMC, tarifs duree).
        $objectifModel = new ObjectifModel();
        $normeModel = new NormeImcModel();
        $tarifModel = new TarifDureeModel();

        $data = $this->baseData('Back office - Parametres');
        $data['objectifs'] = $objectifModel->orderBy('libelle', 'ASC')->findAll();
        $data['normes'] = $normeModel->orderBy('v_min', 'ASC')->findAll();
        $data['tarifs'] = $tarifModel->orderBy('duree_min', 'ASC')->findAll();
        $data['errors'] = session()->getFlashdata('errors') ?? [];

        return view('back_office/parametres', $data);
    }

    public function createObjectif()
    {
        helper('form');

        $data = $this->baseData('Back office - Nouvel objectif');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = false;
        $data['objectif'] = null;

        return view('back_office/parametres_objectif_form', $data);
    }

    public function storeObjectif()
    {
        $objectifModel = new ObjectifModel();
        $payload = $this->request->getPost();

        if (!$objectifModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $objectifModel->errors());
        }

        $objectifModel->insert($payload);

        return redirect()->to('/back-office/parametres')->with('success', 'Objectif cree.');
    }

    public function editObjectif(int $id)
    {
        helper('form');

        $objectifModel = new ObjectifModel();
        $objectif = $objectifModel->find($id);

        if (!$objectif) {
            return redirect()->to('/back-office/parametres')->with('error', 'Objectif introuvable.');
        }

        $data = $this->baseData('Back office - Modifier objectif');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = true;
        $data['objectif'] = $objectif;

        return view('back_office/parametres_objectif_form', $data);
    }

    public function updateObjectif(int $id)
    {
        $objectifModel = new ObjectifModel();
        $objectif = $objectifModel->find($id);

        if (!$objectif) {
            return redirect()->to('/back-office/parametres')->with('error', 'Objectif introuvable.');
        }

        $payload = $this->request->getPost();
        if (!$objectifModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $objectifModel->errors());
        }

        $objectifModel->update($id, $payload);

        return redirect()->to('/back-office/parametres')->with('success', 'Objectif mis a jour.');
    }

    public function deleteObjectif(int $id)
    {
        $objectifModel = new ObjectifModel();
        $objectif = $objectifModel->find($id);

        if (!$objectif) {
            return redirect()->to('/back-office/parametres')->with('error', 'Objectif introuvable.');
        }

        $db = \Config\Database::connect();
        $hasRegime = $db->table('regime')->where('id_objectif', $id)->countAllResults() > 0;
        $hasActivite = $db->table('activite_sportive')->where('id_objectif', $id)->countAllResults() > 0;
        if ($hasRegime || $hasActivite) {
            return redirect()->to('/back-office/parametres')->with('error', 'Suppression impossible: objectif utilise.');
        }

        $objectifModel->delete($id);

        return redirect()->to('/back-office/parametres')->with('success', 'Objectif supprime.');
    }

    public function createNorme()
    {
        helper('form');

        $data = $this->baseData('Back office - Nouvelle norme IMC');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = false;
        $data['norme'] = null;

        return view('back_office/parametres_norme_form', $data);
    }

    public function storeNorme()
    {
        $normeModel = new NormeImcModel();
        $payload = $this->request->getPost();

        $validationError = $this->validateRange($payload['v_min'] ?? null, $payload['v_max'] ?? null);
        if ($validationError !== null) {
            return redirect()->back()->withInput()->with('errors', [$validationError]);
        }

        if (!$normeModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $normeModel->errors());
        }

        $normeModel->insert($payload);

        return redirect()->to('/back-office/parametres')->with('success', 'Norme IMC creee.');
    }

    public function editNorme(int $id)
    {
        helper('form');

        $normeModel = new NormeImcModel();
        $norme = $normeModel->find($id);

        if (!$norme) {
            return redirect()->to('/back-office/parametres')->with('error', 'Norme IMC introuvable.');
        }

        $data = $this->baseData('Back office - Modifier norme IMC');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = true;
        $data['norme'] = $norme;

        return view('back_office/parametres_norme_form', $data);
    }

    public function updateNorme(int $id)
    {
        $normeModel = new NormeImcModel();
        $norme = $normeModel->find($id);

        if (!$norme) {
            return redirect()->to('/back-office/parametres')->with('error', 'Norme IMC introuvable.');
        }

        $payload = $this->request->getPost();
        $validationError = $this->validateRange($payload['v_min'] ?? null, $payload['v_max'] ?? null);
        if ($validationError !== null) {
            return redirect()->back()->withInput()->with('errors', [$validationError]);
        }

        if (!$normeModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $normeModel->errors());
        }

        $normeModel->update($id, $payload);

        return redirect()->to('/back-office/parametres')->with('success', 'Norme IMC mise a jour.');
    }

    public function deleteNorme(int $id)
    {
        $normeModel = new NormeImcModel();
        $norme = $normeModel->find($id);

        if (!$norme) {
            return redirect()->to('/back-office/parametres')->with('error', 'Norme IMC introuvable.');
        }

        $normeModel->delete($id);

        return redirect()->to('/back-office/parametres')->with('success', 'Norme IMC supprimee.');
    }

    public function createTarif()
    {
        helper('form');

        $data = $this->baseData('Back office - Nouveau tarif');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = false;
        $data['tarif'] = null;

        return view('back_office/parametres_tarif_form', $data);
    }

    public function storeTarif()
    {
        $tarifModel = new TarifDureeModel();
        $payload = $this->normalizeTarifPayload($this->request->getPost());

        $validationError = $this->validateRange($payload['duree_min'] ?? null, $payload['duree_max'] ?? null);
        if ($validationError !== null) {
            return redirect()->back()->withInput()->with('errors', [$validationError]);
        }
        if (isset($payload['coefficient']) && (float) $payload['coefficient'] <= 0) {
            return redirect()->back()->withInput()->with('errors', ['Le coefficient doit etre superieur a 0.']);
        }

        if (!$tarifModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $tarifModel->errors());
        }

        $tarifModel->insert($payload);

        return redirect()->to('/back-office/parametres')->with('success', 'Tarif cree.');
    }

    public function editTarif(int $id)
    {
        helper('form');

        $tarifModel = new TarifDureeModel();
        $tarif = $tarifModel->find($id);

        if (!$tarif) {
            return redirect()->to('/back-office/parametres')->with('error', 'Tarif introuvable.');
        }

        $data = $this->baseData('Back office - Modifier tarif');
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        $data['isEdit'] = true;
        $data['tarif'] = $tarif;

        return view('back_office/parametres_tarif_form', $data);
    }

    public function updateTarif(int $id)
    {
        $tarifModel = new TarifDureeModel();
        $tarif = $tarifModel->find($id);

        if (!$tarif) {
            return redirect()->to('/back-office/parametres')->with('error', 'Tarif introuvable.');
        }

        $payload = $this->normalizeTarifPayload($this->request->getPost());
        $validationError = $this->validateRange($payload['duree_min'] ?? null, $payload['duree_max'] ?? null);
        if ($validationError !== null) {
            return redirect()->back()->withInput()->with('errors', [$validationError]);
        }
        if (isset($payload['coefficient']) && (float) $payload['coefficient'] <= 0) {
            return redirect()->back()->withInput()->with('errors', ['Le coefficient doit etre superieur a 0.']);
        }

        if (!$tarifModel->validate($payload)) {
            return redirect()->back()->withInput()->with('errors', $tarifModel->errors());
        }

        $tarifModel->update($id, $payload);

        return redirect()->to('/back-office/parametres')->with('success', 'Tarif mis a jour.');
    }

    public function deleteTarif(int $id)
    {
        $tarifModel = new TarifDureeModel();
        $tarif = $tarifModel->find($id);

        if (!$tarif) {
            return redirect()->to('/back-office/parametres')->with('error', 'Tarif introuvable.');
        }

        $tarifModel->delete($id);

        return redirect()->to('/back-office/parametres')->with('success', 'Tarif supprime.');
    }

    private function validateRange($minValue, $maxValue): ?string
    {
        if ($minValue === null || $minValue === '' || $maxValue === '' || $maxValue === null) {
            return null;
        }

        if (!is_numeric($minValue) || !is_numeric($maxValue)) {
            return 'Les bornes doivent etre numeriques.';
        }

        if ((float) $minValue > (float) $maxValue) {
            return 'La borne minimale doit etre inferieure ou egale a la borne maximale.';
        }

        return null;
    }

    private function normalizeTarifPayload(array $payload): array
    {
        if (array_key_exists('duree_max', $payload) && $payload['duree_max'] === '') {
            $payload['duree_max'] = null;
        }

        return $payload;
    }

    private function getDashboardData(): array
    {
        $db = \Config\Database::connect();

        $stats = [
            'users' => $db->table('user')->countAllResults(),
            'admins' => $db->table('user')->where('id_statut', 2)->countAllResults(),
            'regimes' => $db->table('regime')->countAllResults(),
            'activites' => $db->table('activite_sportive')->countAllResults(),
            'programmes' => $db->table('regime_selection')->countAllResults(),
            'codes_utilises' => $db->table('code_user')->countAllResults(),
        ];

        $montantRow = $db->table('code_user')
            ->selectSum('code.montant', 'total')
            ->join('code', 'code.id_code = code_user.id_code', 'left')
            ->get()
            ->getRowArray();
        $stats['montant_codes'] = (float) ($montantRow['total'] ?? 0);

        $objectifStats = $db->table('objectif')
            ->select('objectif.libelle, COUNT(DISTINCT regime.id_regime) as regimes_count, COUNT(DISTINCT activite_sportive.id_activite) as activites_count')
            ->join('regime', 'regime.id_objectif = objectif.id_objectif', 'left')
            ->join('activite_sportive', 'activite_sportive.id_objectif = objectif.id_objectif', 'left')
            ->groupBy('objectif.id_objectif')
            ->orderBy('objectif.libelle', 'ASC')
            ->get()
            ->getResultArray();

        $selectionStats = $db->table('regime_selection')
            ->select('objectif, COUNT(*) as total')
            ->groupBy('objectif')
            ->orderBy('objectif', 'ASC')
            ->get()
            ->getResultArray();

        return [
            'stats' => $stats,
            'objectifStats' => $objectifStats,
            'selectionStats' => $selectionStats,
        ];
    }
}
