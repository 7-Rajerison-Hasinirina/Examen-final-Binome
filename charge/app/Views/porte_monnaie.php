<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php
$user = $user ?? [];
$historique = $historique ?? [];
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
        <h1>Porte-monnaie</h1>
        <p>Ajoutez un code pour crediter votre solde.</p>
    </div>
</div>

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

<div style="margin-top: 16px; display:grid; gap: 16px;">
    <div style="padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px;">
        <h2 style="margin-bottom: 8px;">Solde actuel</h2>
        <div style="font-size: 1.4rem; font-weight: 600; color: #10b981;">
            <?= esc((string) ($user['porte_monnaie'] ?? '0.00')) ?> Ar
        </div>
    </div>

    <form method="post" action="<?= base_url('porte-monnaie/utiliser') ?>" style="padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; max-width: 420px;">
        <?= csrf_field() ?>
        <div style="display:grid; gap: 12px;">
            <div>
                <label for="code">Code</label>
                <input type="text" name="code" id="code" placeholder="Ex: CD34" value="<?= esc((string) old('code')) ?>" required>
            </div>
            <button type="submit" class="submit-btn">Utiliser le code</button>
        </div>
    </form>

    <div style="padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px;">
        <h2 style="margin-bottom: 8px;">Historique des codes</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left; padding: 8px;">Code</th>
                        <th style="text-align:left; padding: 8px;">Montant</th>
                        <th style="text-align:left; padding: 8px;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)) : ?>
                        <tr>
                            <td colspan="3" style="padding: 12px;">Aucun code utilise.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($historique as $entry) : ?>
                        <tr>
                            <td style="padding: 8px;"><?= esc((string) $entry['libelle']) ?></td>
                            <td style="padding: 8px;"><?= esc((string) $entry['montant']) ?></td>
                            <td style="padding: 8px;"><?= esc((string) $entry['date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
