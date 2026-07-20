<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php
$objectifs = $objectifs ?? [];
$normes = $normes ?? [];
$tarifs = $tarifs ?? [];
$flashError = session()->getFlashdata('error');
$flashErrorText = '';
if (!empty($flashError)) {
    $flashErrorText = is_array($flashError) ? implode(' | ', $flashError) : (string) $flashError;
}
$flashSuccess = session()->getFlashdata('success');
$flashSuccessText = '';
if (!empty($flashSuccess)) {
    $flashSuccessText = is_array($flashSuccess) ? implode(' | ', $flashSuccess) : (string) $flashSuccess;
}
?>
<div class="page-header" style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
    <div>
        <h1>Parametres</h1>
        <p>Objectifs, normes IMC et tarifs duree.</p>
    </div>
</div>

<?php if (!empty($errors)) : ?>
    <div class="form-feedback" role="alert" style="margin-top: 12px;">
        <?= esc(implode(' | ', $errors)) ?>
    </div>
<?php endif; ?>

<?php if ($flashErrorText !== '') : ?>
    <div class="form-feedback" role="alert" style="margin-top: 12px;">
        <?= esc($flashErrorText) ?>
    </div>
<?php endif; ?>

<?php if ($flashSuccessText !== '') : ?>
    <div class="form-feedback" role="status" style="margin-top: 12px;">
        <?= esc($flashSuccessText) ?>
    </div>
<?php endif; ?>

<section style="margin-top: 20px;">
    <div class="page-header" style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
        <h2>Objectifs</h2>
        <a class="submit-btn" href="<?= base_url('back-office/parametres/objectifs/create') ?>" style="text-decoration: none;">Nouvel objectif</a>
    </div>
    <div style="margin-top: 12px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align:left; padding: 8px;">Libelle</th>
                    <th style="text-align:left; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($objectifs)) : ?>
                    <tr>
                        <td colspan="2" style="padding: 12px;">Aucun objectif.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($objectifs as $objectif) : ?>
                    <tr>
                        <td style="padding: 8px;"><?= esc((string) $objectif['libelle']) ?></td>
                        <td style="padding: 8px;">
                            <a href="<?= base_url('back-office/parametres/objectifs/' . $objectif['id_objectif'] . '/edit') ?>">Modifier</a>
                            <form method="post" action="<?= base_url('back-office/parametres/objectifs/' . $objectif['id_objectif'] . '/delete') ?>" style="display:inline-block; margin-left: 8px;" onsubmit="return confirm('Supprimer cet objectif ?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="submit-btn" style="padding: 6px 10px;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section style="margin-top: 28px;">
    <div class="page-header" style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
        <h2>Normes IMC</h2>
        <a class="submit-btn" href="<?= base_url('back-office/parametres/normes/create') ?>" style="text-decoration: none;">Nouvelle norme</a>
    </div>
    <div style="margin-top: 12px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align:left; padding: 8px;">Libelle</th>
                    <th style="text-align:left; padding: 8px;">Valeur min</th>
                    <th style="text-align:left; padding: 8px;">Valeur max</th>
                    <th style="text-align:left; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($normes)) : ?>
                    <tr>
                        <td colspan="4" style="padding: 12px;">Aucune norme.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($normes as $norme) : ?>
                    <tr>
                        <td style="padding: 8px;"><?= esc((string) $norme['libelle']) ?></td>
                        <td style="padding: 8px;"><?= esc((string) $norme['v_min']) ?></td>
                        <td style="padding: 8px;"><?= esc((string) $norme['v_max']) ?></td>
                        <td style="padding: 8px;">
                            <a href="<?= base_url('back-office/parametres/normes/' . $norme['id_norme'] . '/edit') ?>">Modifier</a>
                            <form method="post" action="<?= base_url('back-office/parametres/normes/' . $norme['id_norme'] . '/delete') ?>" style="display:inline-block; margin-left: 8px;" onsubmit="return confirm('Supprimer cette norme ?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="submit-btn" style="padding: 6px 10px;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section style="margin-top: 28px;">
    <div class="page-header" style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
        <h2>Tarifs duree</h2>
        <a class="submit-btn" href="<?= base_url('back-office/parametres/tarifs/create') ?>" style="text-decoration: none;">Nouveau tarif</a>
    </div>
    <div style="margin-top: 12px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align:left; padding: 8px;">Libelle</th>
                    <th style="text-align:left; padding: 8px;">Duree min</th>
                    <th style="text-align:left; padding: 8px;">Duree max</th>
                    <th style="text-align:left; padding: 8px;">Coefficient</th>
                    <th style="text-align:left; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tarifs)) : ?>
                    <tr>
                        <td colspan="5" style="padding: 12px;">Aucun tarif.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($tarifs as $tarif) : ?>
                    <tr>
                        <td style="padding: 8px;"><?= esc((string) $tarif['libelle']) ?></td>
                        <td style="padding: 8px;"><?= esc((string) $tarif['duree_min']) ?></td>
                        <td style="padding: 8px;"><?= esc((string) ($tarif['duree_max'] ?? '-')) ?></td>
                        <td style="padding: 8px;"><?= esc((string) $tarif['coefficient']) ?></td>
                        <td style="padding: 8px;">
                            <a href="<?= base_url('back-office/parametres/tarifs/' . $tarif['id_tarif'] . '/edit') ?>">Modifier</a>
                            <form method="post" action="<?= base_url('back-office/parametres/tarifs/' . $tarif['id_tarif'] . '/delete') ?>" style="display:inline-block; margin-left: 8px;" onsubmit="return confirm('Supprimer ce tarif ?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="submit-btn" style="padding: 6px 10px;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
