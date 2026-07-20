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
?>

<section class="hero-panel">
    <div class="hero-copy">
        <div class="hero-kicker">Tableau de bord opérateur</div>
        <h2>Bonjour <?= esc($nom ?? '') ?></h2>
        <p>Ce tableau rassemble la configuration des préfixes, la création des types d'opérations, la situation de gain et la situation des comptes.</p>
        <div class="hero-badges">
            <span class="tag tag-neutral">Compte opérateur</span>
            <span class="tag tag-success">Base synchronisée</span>
            <span class="tag tag-warning">Accès direct</span>
        </div>
    </div>

    <div class="hero-summary">
        <div class="summary-label">Session active</div>
        <div class="summary-name"><?= esc($nom ?? 'Opérateur') ?></div>
        <div class="summary-line">Numéro: <?= esc($numero ?? '-') ?></div>
        <div class="summary-line">Identifiant: <?= esc((string) ($user_id ?? '')) ?></div>
    </div>
</section>

<section class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Préfixes opérateur</div>
        <div class="stat-value"><?= esc((string) ($stats['operateurs'] ?? 0)) ?></div>
        <div class="stat-note">Préfixes chargés depuis la table opérateur.</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Comptes opérateur</div>
        <div class="stat-value"><?= esc((string) ($stats['comptes_operateur'] ?? 0)) ?></div>
        <div class="stat-note">Utilisateurs avec le rôle opérateur.</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Numéros enregistrés</div>
        <div class="stat-value"><?= esc((string) ($stats['numeros'] ?? 0)) ?></div>
        <div class="stat-note">Ensemble des numéros présents en base.</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Opérations récentes</div>
        <div class="stat-value"><?= esc((string) ($stats['operations'] ?? 0)) ?></div>
        <div class="stat-note">Activité récente visible dans le tableau.</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Dépôts</div>
        <div class="stat-value"><?= esc((string) ($stats['depots'] ?? 0)) ?></div>
        <div class="stat-note">Opérations créditées sur les comptes.</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Retraits / Transferts</div>
        <div class="stat-value"><?= esc((string) ($stats['retraits'] ?? 0)) ?> / <?= esc((string) ($stats['transferts'] ?? 0)) ?></div>
        <div class="stat-note">Flux sortants et mouvements internes.</div>
    </div>
</section>

<section id="prefixes" class="dashboard-grid">
    <article class="panel">
        <div class="panel-head">
            <div>
                <h3>Configuration des préfixes</h3>
                <span>Ajouter un préfixe et voir les opérateurs déjà enregistrés.</span>
            </div>
        </div>
        <div class="panel-body">
            <form method="post" action="<?= base_url('operateur-office') ?>" class="mini-grid" style="margin-bottom: 1rem;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="prefixe">
                <div class="mini-card">
                    <label for="prefixe" style="display:block; margin-bottom:0.4rem; font-weight:700; color: var(--primary-dark);">Préfixe</label>
                    <div class="mini-head"><span></span><span class="tag tag-neutral">3 chiffres</span></div>
                    <input id="prefixe" type="text" name="prefixe" maxlength="3" minlength="3" required class="form-control" placeholder="038">
                </div>
                <div class="mini-card">
                    <label for="operateur" style="display:block; margin-bottom:0.4rem; font-weight:700; color: var(--primary-dark);">Opérateur</label>
                    <div class="mini-head"><span></span><span class="tag tag-success">Nom</span></div>
                    <input id="operateur" type="text" name="operateur" maxlength="50" required class="form-control" placeholder="Orange Money">
                </div>
                <div class="mini-card" style="display:flex; align-items:end;">
                    <button type="submit" class="pill-btn pill-danger" style="width:100%;">Enregistrer le préfixe</button>
                </div>
            </form>

            <?php if (empty($operateurs)): ?>
                <div class="empty-state">Aucun opérateur trouvé en base.</div>
            <?php else: ?>
                <div class="mini-grid">
                    <?php foreach ($operateurs as $operateur): ?>
                        <div class="mini-card">
                            <div class="mini-head">
                                <strong><?= esc($operateur['operateur']) ?></strong>
                                <span class="tag tag-success"><?= esc($operateur['prefixe']) ?></span>
                            </div>
                            <div class="mini-meta">Préfixe mobile enregistré</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </article>

    <article id="types" class="panel">
        <div class="panel-head">
            <div>
                <h3>Création des types d'opérations</h3>
                <span>Ajouter un type et vérifier ceux déjà présents.</span>
            </div>
        </div>
        <div class="panel-body">
            <form method="post" action="<?= base_url('operateur-office') ?>" class="mini-grid" style="margin-bottom: 1rem;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="type_operation">
                <div class="mini-card">
                    <label for="libelle" style="display:block; margin-bottom:0.4rem; font-weight:700; color: var(--primary-dark);">Libellé</label>
                    <div class="mini-head"><span></span><span class="tag tag-neutral">Nom</span></div>
                    <input id="libelle" type="text" name="libelle" maxlength="50" required class="form-control" placeholder="Depot">
                </div>
                <div class="mini-card" style="display:flex; align-items:end;">
                    <button type="submit" class="pill-btn pill-neutral" style="width:100%;">Créer le type</button>
                </div>
            </form>

            <div class="mini-grid">
                <?php if (empty($typesOperations)): ?>
                    <div class="empty-state">Aucun type d'opération trouvé.</div>
                <?php else: ?>
                    <?php foreach ($typesOperations as $type): ?>
                        <div class="mini-card">
                            <div class="mini-head">
                                <strong><?= esc($type['libelle']) ?></strong>
                                <span class="tag tag-warning">Type</span>
                            </div>
                            <div class="mini-meta">Type d'opération disponible</div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </article>
</section>

<section id="gains" class="dashboard-grid" style="margin-top: 1rem;">
    <article class="panel">
        <div class="panel-head">
            <div>
                <h3>Situation de gain</h3>
                <span>Vue rapide des entrées, sorties et du gain net.</span>
            </div>
        </div>
        <div class="panel-body">
            <div class="mini-grid">
                <div class="mini-card">
                    <div class="mini-head"><strong>Total entrées</strong><span class="tag tag-success">Gain brut</span></div>
                    <div class="stat-value"><?= number_format((float) ($gainStats['total_entrees'] ?? 0), 2, ',', ' ') ?> Ar</div>
                </div>
                <div class="mini-card">
                    <div class="mini-head"><strong>Total sorties</strong><span class="tag tag-warning">Pertes</span></div>
                    <div class="stat-value"><?= number_format((float) ($gainStats['total_sorties'] ?? 0), 2, ',', ' ') ?> Ar</div>
                </div>
                <div class="mini-card">
                    <div class="mini-head"><strong>Gain net</strong><span class="tag tag-neutral">Net</span></div>
                    <div class="stat-value"><?= number_format((float) ($gainStats['gain_net'] ?? 0), 2, ',', ' ') ?> Ar</div>
                </div>
            </div>
        </div>
    </article>

    <article id="comptes" class="panel">
        <div class="panel-head">
            <div>
                <h3>Situation des comptes</h3>
                <span>Répartition des utilisateurs et des numéros en base.</span>
            </div>
        </div>
        <div class="panel-body">
            <div class="mini-grid" style="margin-bottom: 1rem;">
                <div class="mini-card"><div class="mini-head"><strong>Clients</strong><span class="tag tag-neutral"><?= esc((string) ($accountStats['clients'] ?? 0)) ?></span></div><div class="mini-meta">Comptes clients enregistrés</div></div>
                <div class="mini-card"><div class="mini-head"><strong>Opérateurs</strong><span class="tag tag-success"><?= esc((string) ($accountStats['operateurs'] ?? 0)) ?></span></div><div class="mini-meta">Comptes opérateur enregistrés</div></div>
                <div class="mini-card"><div class="mini-head"><strong>Numéros</strong><span class="tag tag-warning"><?= esc((string) ($accountStats['numeros'] ?? 0)) ?></span></div><div class="mini-meta">Numéros liés aux comptes</div></div>
            </div>

            <div class="mini-grid">
                <div class="mini-card">
                    <div class="mini-head"><strong>Comptes opérateur</strong><span class="tag tag-neutral">Rôle 3</span></div>
                    <?php if (empty($comptesOperateur)): ?>
                        <div class="empty-state">Aucun compte opérateur.</div>
                    <?php else: ?>
                        <?php foreach ($comptesOperateur as $compte): ?>
                            <div class="mini-meta"><?= esc($compte['nom']) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="mini-card">
                    <div class="mini-head"><strong>Comptes clients</strong><span class="tag tag-success">Rôle 2</span></div>
                    <?php if (empty($comptesClients)): ?>
                        <div class="empty-state">Aucun compte client.</div>
                    <?php else: ?>
                        <?php foreach ($comptesClients as $compte): ?>
                            <div class="mini-meta"><?= esc($compte['nom']) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</section>

<section class="dashboard-grid" style="margin-top: 1rem;">
    <article class="panel">
        <div class="panel-head">
            <div>
                <h3>Opérations récentes</h3>
                <span>Derniers mouvements détectés en base.</span>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Type</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Montant</th>
                        <th>Sens</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentOperations)): ?>
                        <tr>
                            <td colspan="7"><div class="empty-state">Aucune opération récente.</div></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOperations as $operation): ?>
                            <tr>
                                <td><?= esc(date('d/m/Y H:i', strtotime((string) $operation['date']))) ?></td>
                                <td><?= esc($operation['utilisateur'] ?? '-') ?></td>
                                <td><?= esc($operation['type'] ?? '-') ?></td>
                                <td><?= esc($operation['numero_source'] ?? '-') ?></td>
                                <td><?= esc($operation['numero_destination'] ?? '-') ?></td>
                                <td><?= number_format((float) ($operation['valeur'] ?? 0), 2, ',', ' ') ?> Ar</td>
                                <td>
                                    <?php if (($operation['sens'] ?? 'entree') === 'entree'): ?>
                                        <span class="tag tag-success">Entrée</span>
                                    <?php else: ?>
                                        <span class="tag tag-warning">Sortie</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </article>

    <aside class="panel">
        <div class="panel-head">
            <div>
                <h3>Raccourcis sûrs</h3>
                <span>Actions sans risque de route manquante.</span>
            </div>
        </div>
        <div class="panel-body">
            <div class="shortcut-stack">
                <a href="<?= base_url('operateur-office') ?>" class="shortcut-link">
                    <strong>Actualiser le tableau</strong>
                    <span>Recharger les statistiques</span>
                </a>
                <a href="<?= base_url('operateur-office/logout') ?>" class="shortcut-link shortcut-danger">
                    <strong>Déconnexion</strong>
                    <span>Quitter la session</span>
                </a>
            </div>
            <div class="shortcut-note">
                L’espace opérateur s’appuie désormais sur les tables déjà présentes en base et évite les écrans vides ou les boutons cassés.
            </div>
        </div>
    </aside>
</section>
<?= $this->endSection() ?>
