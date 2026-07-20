<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<style>
    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .header-row h2 {
        margin: 0;
        color: var(--primary-dark);
    }

    .balance-pill {
        padding: 1rem 1.1rem;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(0, 168, 107, 0.09), rgba(0, 212, 255, 0.09));
        border: 1px solid rgba(36, 74, 134, 0.12);
        min-width: 220px;
    }

    .balance-pill .label {
        color: var(--text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .balance-pill .value {
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--success);
    }

    .table-wrap {
        overflow-x: auto;
        border-radius: 18px;
        border: 1px solid rgba(36, 74, 134, 0.12);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    thead th {
        text-align: left;
        padding: 0.95rem;
        background: #eef5ff;
        color: var(--primary-dark);
        border-bottom: 1px solid rgba(36, 74, 134, 0.12);
        white-space: nowrap;
    }

    tbody td {
        padding: 0.95rem;
        border-bottom: 1px solid rgba(36, 74, 134, 0.08);
        white-space: nowrap;
    }

    tbody tr:hover {
        background: rgba(0, 212, 255, 0.04);
    }

    .tag-in {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: rgba(0, 168, 107, 0.12);
        color: #0f7a50;
        font-weight: 700;
    }

    .tag-out {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: rgba(224, 86, 86, 0.12);
        color: #b53d3d;
        font-weight: 700;
    }

    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        color: var(--text-muted);
    }
</style>

<div class="header-row">
    <div>
        <h2>Historique du numéro actif</h2>
        <div style="color: var(--text-muted);">Les opérations affichées concernent seulement le numéro sélectionné dans le layout.</div>
    </div>

    <?php if (!empty($numeroActif)): ?>
        <div class="balance-pill">
            <div class="label">Numéro actif</div>
            <div style="font-weight: 800; color: var(--primary-dark);"><?= esc($numeroActif['prefixe']) ?> <?= esc($numeroActif['numero']) ?></div>
            <div class="value"><?= number_format((float) ($numeroActif['solde'] ?? 0), 2, ',', ' ') ?> Ar</div>
        </div>
    <?php endif; ?>
</div>

<?php if (empty($numeroActif)): ?>
    <div class="empty-state">Aucun numéro n’est disponible pour afficher l’historique.</div>
<?php elseif (empty($operations)): ?>
    <div class="empty-state">Aucune opération pour ce numéro pour le moment.</div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Montant</th>
                    <th>Référence</th>
                    <th>Sens</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operations as $op): ?>
                    <tr>
                        <td><?= esc(date('d/m/Y H:i', strtotime($op['date']))) ?></td>
                        <td><?= esc($op['type'] ?? '') ?></td>
                        <td><?= esc($op['numero_source'] ?? '-') ?></td>
                        <td><?= esc($op['numero_destination'] ?? '-') ?></td>
                        <td><?= number_format((float) ($op['valeur'] ?? 0), 2, ',', ' ') ?> Ar</td>
                        <td><?= esc($op['reference'] ?? '-') ?></td>
                        <td>
                            <?php if (($op['sens'] ?? 'entree') === 'entree'): ?>
                                <span class="tag-in"><i class="fas fa-arrow-down"></i> Entrée</span>
                            <?php else: ?>
                                <span class="tag-out"><i class="fas fa-arrow-up"></i> Sortie</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div style="margin-top: 1.25rem;">
    <a href="<?= base_url('client-office') ?>" class="pill pill-neutral"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
</div>
<?= $this->endSection() ?>