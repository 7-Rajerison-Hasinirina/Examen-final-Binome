<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $isEdit ?? false;
$tarif = $tarif ?? [];
$errors = $errors ?? [];
$actionUrl = $isEdit && !empty($tarif)
    ? base_url('back-office/parametres/tarifs/' . $tarif['id_tarif'])
    : base_url('back-office/parametres/tarifs');
?>
<div class="page-header" style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
    <div>
        <h1><?= $isEdit ? 'Modifier le tarif' : 'Nouveau tarif' ?></h1>
        <p>Associez une duree a un coefficient.</p>
    </div>
    <a class="submit-btn" href="<?= base_url('back-office/parametres') ?>" style="text-decoration: none;">Retour</a>
</div>

<?php if (!empty($errors)) : ?>
    <div class="form-feedback" role="alert" style="margin-top: 12px;">
        <?= esc(implode(' | ', $errors)) ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= $actionUrl ?>" style="margin-top: 16px; max-width: 520px;">
    <?= csrf_field() ?>
    <div style="display:grid; gap: 12px;">
        <div>
            <label for="libelle">Libelle</label>
            <input type="text" name="libelle" id="libelle" value="<?= esc((string) old('libelle', $tarif['libelle'] ?? '')) ?>" required>
        </div>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px;">
            <div>
                <label for="duree_min">Duree min (jours)</label>
                <input type="number" name="duree_min" id="duree_min" value="<?= esc((string) old('duree_min', $tarif['duree_min'] ?? '')) ?>" required>
            </div>
            <div>
                <label for="duree_max">Duree max (jours)</label>
                <input type="number" name="duree_max" id="duree_max" value="<?= esc((string) old('duree_max', $tarif['duree_max'] ?? '')) ?>">
            </div>
        </div>
        <div>
            <label for="coefficient">Coefficient</label>
            <input type="number" step="0.01" name="coefficient" id="coefficient" value="<?= esc((string) old('coefficient', $tarif['coefficient'] ?? '')) ?>" required>
        </div>
        <button type="submit" class="submit-btn"><?= $isEdit ? 'Mettre a jour' : 'Creer' ?></button>
    </div>
</form>
<?= $this->endSection() ?>
