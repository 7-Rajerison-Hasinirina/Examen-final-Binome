<?= $this->extend('operateur/layout') ?>

<?= $this->section('content') ?>
<?php
$stats = $stats ?? [];
$recentOperations = $recentOperations ?? [];
$operateurs = $operateurs ?? [];
$typesOperations = $typesOperations ?? [];
$gainStats = $gainStats ?? [];
$accountStats = $accountStats ?? [];
$comptesOperateur = $comptesOperateur ?? [];
$comptesClients = $comptesClients ?? [];
 $clientsOperateur = $clientsOperateur ?? [];
 $clientsOperateurGrouped = $clientsOperateurGrouped ?? [];
?>

<div class="tab-content" id="operateur-tabs-content">

    <!-- ============ TABLEAU DE BORD (affiché par défaut) ============ -->
    <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel" aria-labelledby="tab-btn-dashboard">

        <div class="card text-bg-primary mb-4">
            <div class="card-body p-4">
                <span class="badge text-bg-light text-primary mb-2">Tableau de bord opérateur</span>
                <h2 class="card-title">Bonjour <?= esc($nom ?? '') ?></h2>
                <p class="card-text mb-0">
                    Ce tableau rassemble la configuration des préfixes, la création des types d'opérations,
                    la situation de gain et la situation des comptes.
                </p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-6 g-3 mb-4">
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Préfixes opérateur</div>
                        <div class="fs-3 fw-bold"><?= esc((string) ($stats['operateurs'] ?? 0)) ?></div>
                        <div class="small text-muted">Depuis la table opérateur</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Comptes opérateur</div>
                        <div class="fs-3 fw-bold"><?= esc((string) ($stats['comptes_operateur'] ?? 0)) ?></div>
                        <div class="small text-muted">Utilisateurs rôle opérateur</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Numéros enregistrés</div>
                        <div class="fs-3 fw-bold"><?= esc((string) ($stats['numeros'] ?? 0)) ?></div>
                        <div class="small text-muted">Numéros en base</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Opérations récentes</div>
                        <div class="fs-3 fw-bold"><?= esc((string) ($stats['operations'] ?? 0)) ?></div>
                        <div class="small text-muted">Activité récente</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Dépôts</div>
                        <div class="fs-3 fw-bold"><?= esc((string) ($stats['depots'] ?? 0)) ?></div>
                        <div class="small text-muted">Opérations créditées</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Retraits / Transferts</div>
                        <div class="fs-3 fw-bold"><?= esc((string) ($stats['retraits'] ?? 0)) ?> / <?= esc((string) ($stats['transferts'] ?? 0)) ?></div>
                        <div class="small text-muted">Flux sortants / internes</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h6 mb-0">Dernières opérations</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Sens</th>
                            <th class="text-end">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOperations)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucune opération récente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach (array_slice($recentOperations, 0, 5) as $operation): ?>
                                <tr>
                                    <td><?= esc(date('d/m/Y H:i', strtotime((string) $operation['date']))) ?></td>
                                    <td><?= esc($operation['utilisateur'] ?? '-') ?></td>
                                    <td><?= esc($operation['type'] ?? '-') ?></td>
                                    <td>
                                        <?php if (($operation['sens'] ?? 'entree') === 'entree'): ?>
                                            <span class="badge text-bg-success">Entrée</span>
                                        <?php else: ?>
                                            <span class="badge text-bg-warning">Sortie</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?= number_format((float) ($operation['valeur'] ?? 0), 2, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-end">
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="pill" data-bs-target="#tab-operations">
                    Voir toutes les opérations
                </button>
                <button class="btn btn-sm btn-outline-secondary ms-2" type="button" data-bs-toggle="pill" data-bs-target="#tab-clients">
                    Clients de l'opérateur
                </button>
            </div>
        </div>
    </div>

    <!-- ============ CONFIGURATION DES PREFIXES ============ -->
    <div class="tab-pane fade" id="tab-prefixes" role="tabpanel" aria-labelledby="tab-btn-prefixes">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h3 class="h6 mb-0">Ajouter un préfixe</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operateur-office') ?>" class="row g-3 align-items-end">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="prefixe">
                    <div class="col-12 col-md-4">
                        <label for="prefixe" class="form-label fw-semibold">Préfixe <span class="badge text-bg-secondary">3 chiffres</span></label>
                        <input id="prefixe" type="text" name="prefixe" maxlength="3" minlength="3" required class="form-control" placeholder="038">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="operateur" class="form-label fw-semibold">Opérateur <span class="badge text-bg-success">Nom</span></label>
                        <input id="operateur" type="text" name="operateur" maxlength="50" required class="form-control" placeholder="Orange Money">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-danger w-100">Enregistrer le préfixe</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h6 mb-0">Préfixes enregistrés</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Opérateur</th>
                            <th>Préfixe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($operateurs)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">Aucun opérateur trouvé en base.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($operateurs as $operateur): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($operateur['operateur']) ?></td>
                                    <td><span class="badge text-bg-success"><?= esc($operateur['prefixe']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============ TYPES D'OPERATIONS ============ -->
    <div class="tab-pane fade" id="tab-types" role="tabpanel" aria-labelledby="tab-btn-types">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h3 class="h6 mb-0">Créer un type d'opération</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operateur-office') ?>" class="row g-3 align-items-end">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="type_operation">
                    <div class="col-12 col-md-8">
                        <label for="libelle" class="form-label fw-semibold">Libellé</label>
                        <input id="libelle" type="text" name="libelle" maxlength="50" required class="form-control" placeholder="Depot">
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Créer le type</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h6 mb-0">Types d'opérations disponibles</h3>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (empty($typesOperations)): ?>
                    <li class="list-group-item text-center text-muted py-4">Aucun type d'opération trouvé.</li>
                <?php else: ?>
                    <?php foreach ($typesOperations as $type): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= esc($type['libelle']) ?>
                            <span class="badge text-bg-warning">Type</span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- ============ SITUATION DE GAIN ============ -->
    <div class="tab-pane fade" id="tab-gains" role="tabpanel" aria-labelledby="tab-btn-gains">
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <div class="col">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Total entrées</span>
                            <span class="badge text-bg-success">Gain brut</span>
                        </div>
                        <div class="fs-3 fw-bold"><?= number_format((float) ($gainStats['total_entrees'] ?? 0), 2, ',', ' ') ?> Ar</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Total sorties</span>
                            <span class="badge text-bg-warning">Pertes</span>
                        </div>
                        <div class="fs-3 fw-bold"><?= number_format((float) ($gainStats['total_sorties'] ?? 0), 2, ',', ' ') ?> Ar</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Gain net</span>
                            <span class="badge text-bg-primary">Net</span>
                        </div>
                        <div class="fs-3 fw-bold"><?= number_format((float) ($gainStats['gain_net'] ?? 0), 2, ',', ' ') ?> Ar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ SITUATION DES COMPTES ============ -->
    <div class="tab-pane fade" id="tab-comptes" role="tabpanel" aria-labelledby="tab-btn-comptes">
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Clients</span>
                        <span class="badge text-bg-secondary fs-6"><?= esc((string) ($accountStats['clients'] ?? 0)) ?></span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Opérateurs</span>
                        <span class="badge text-bg-success fs-6"><?= esc((string) ($accountStats['operateurs'] ?? 0)) ?></span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Numéros</span>
                        <span class="badge text-bg-warning fs-6"><?= esc((string) ($accountStats['numeros'] ?? 0)) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 g-3">
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Clients de cet opérateur</span>
                        <span class="badge text-bg-secondary">Numéros</span>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (empty($clientsOperateurGrouped)): ?>
                            <div class="list-group-item text-center text-muted py-4">Aucun client trouvé pour cet opérateur.</div>
                        <?php else: ?>
                            <?php foreach ($clientsOperateurGrouped as $client): ?>
                                <?php foreach (($client['numeros'] ?? []) as $num): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold"><?= esc($client['client_nom'] ?? '-') ?></span>
                                        <span class="text-muted"><?= esc($num) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ OPERATIONS RECENTES (liste complète) ============ -->
    <div class="tab-pane fade" id="tab-operations" role="tabpanel" aria-labelledby="tab-btn-operations">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h6 mb-0">Opérations récentes</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th class="text-end">Montant</th>
                            <th>Sens</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOperations)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucune opération récente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentOperations as $operation): ?>
                                <tr>
                                    <td><?= esc(date('d/m/Y H:i', strtotime((string) $operation['date']))) ?></td>
                                    <td><?= esc($operation['utilisateur'] ?? '-') ?></td>
                                    <td><?= esc($operation['type'] ?? '-') ?></td>
                                    <td><?= esc($operation['numero_source'] ?? '-') ?></td>
                                    <td><?= esc($operation['numero_destination'] ?? '-') ?></td>
                                    <td class="text-end"><?= number_format((float) ($operation['valeur'] ?? 0), 2, ',', ' ') ?> Ar</td>
                                    <td>
                                        <?php if (($operation['sens'] ?? 'entree') === 'entree'): ?>
                                            <span class="badge text-bg-success">Entrée</span>
                                        <?php else: ?>
                                            <span class="badge text-bg-warning">Sortie</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============ CLIENTS DE L'OPERATEUR ============ -->
    <div class="tab-pane fade" id="tab-clients" role="tabpanel" aria-labelledby="tab-btn-clients">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h6 mb-0">Clients appartenant au même opérateur</h3>
            </div>
            <div class="card-body">
                <?php if (empty($clientsOperateurGrouped)): ?>
                    <div class="text-center text-muted py-4">Aucun client trouvé pour cet opérateur.</div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($clientsOperateurGrouped as $client): ?>
                            <?php foreach (($client['numeros'] ?? []) as $num): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold"><?= esc($client['client_nom'] ?? '-') ?></span>
                                    <span class="text-muted"><?= esc($num) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var hash = location.hash;
    if(hash){
        // try to find a control button that targets this pane
        var btn = document.querySelector('[data-bs-target="' + hash + '"]');
        if(btn){
            btn.click();
        }
    }
});
</script>
