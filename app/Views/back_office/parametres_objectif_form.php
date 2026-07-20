<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $isEdit ?? false;
$objectif = $objectif ?? [];
$errors = $errors ?? [];
$actionUrl = $isEdit && !empty($objectif)
    ? base_url('back-office/parametres/objectifs/' . $objectif['id_objectif'])
    : base_url('back-office/parametres/objectifs');
?>
<div class="page-header" style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
    <div>
        <h1><?= $isEdit ? 'Modifier l objectif' : 'Nouvel objectif' ?></h1>
        <p>Renseignez le libelle de l objectif.</p>
    </div>
    <a class="submit-btn" href="<?= base_url('back-office/parametres') ?>" style="text-decoration: none;">Retour</a>
</div>

<?php if (!empty($errors)) : ?>
    <div class="form-feedback" role="alert" style="margin-top: 12px;">
        <?= esc(implode(' | ', $errors)) ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= $actionUrl ?>" style="margin-top: 16px; max-width: 480px;">
    <?= csrf_field() ?>
    <div style="display:grid; gap: 12px;">
        <div>
            <label for="libelle">Libelle</label>
            <input type="text" name="libelle" id="libelle" value="<?= esc((string) old('libelle', $objectif['libelle'] ?? '')) ?>" required>
        </div>
        <button type="submit" class="submit-btn"><?= $isEdit ? 'Mettre a jour' : 'Creer' ?></button>
    </div>
</form>
<?= $this->endSection() ?>
