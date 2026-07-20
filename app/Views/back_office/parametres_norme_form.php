<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $isEdit ?? false;
$norme = $norme ?? [];
$errors = $errors ?? [];
$actionUrl = $isEdit && !empty($norme)
    ? base_url('back-office/parametres/normes/' . $norme['id_norme'])
    : base_url('back-office/parametres/normes');
?>
<div class="page-header" style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
    <div>
        <h1><?= $isEdit ? 'Modifier la norme IMC' : 'Nouvelle norme IMC' ?></h1>
        <p>Definissez les bornes IMC.</p>
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
            <input type="text" name="libelle" id="libelle" value="<?= esc((string) old('libelle', $norme['libelle'] ?? '')) ?>" required>
        </div>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px;">
            <div>
                <label for="v_min">Valeur min</label>
                <input type="number" step="0.01" name="v_min" id="v_min" value="<?= esc((string) old('v_min', $norme['v_min'] ?? '')) ?>" required>
            </div>
            <div>
                <label for="v_max">Valeur max</label>
                <input type="number" step="0.01" name="v_max" id="v_max" value="<?= esc((string) old('v_max', $norme['v_max'] ?? '')) ?>" required>
            </div>
        </div>
        <button type="submit" class="submit-btn"><?= $isEdit ? 'Mettre a jour' : 'Creer' ?></button>
    </div>
</form>
<?= $this->endSection() ?>
