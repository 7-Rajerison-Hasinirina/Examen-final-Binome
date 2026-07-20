<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<style>
    .form-shell {
        max-width: 820px;
        display: grid;
        gap: 1rem;
    }

    .notice {
        padding: 1rem 1.1rem;
        border-radius: 16px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(36, 74, 134, 0.14);
        color: var(--primary-dark);
        font-weight: 600;
    }

    .mini-card {
        padding: 1rem 1.1rem;
        border-radius: 16px;
        border: 1px solid rgba(36, 74, 134, 0.12);
        background: linear-gradient(180deg, #ffffff, #f8fbff);
    }

    .mini-card .label {
        color: var(--text-muted);
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .mini-card .value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary-dark);
    }

    .field label {
        display: block;
        margin-bottom: 0.4rem;
        font-weight: 700;
        color: var(--primary-dark);
    }

    .field input,
    .field textarea {
        width: 100%;
        padding: 0.85rem 0.95rem;
        border-radius: 14px;
        border: 1px solid rgba(36, 74, 134, 0.16);
        background: white;
    }

    .submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.9rem 1.15rem;
        border: none;
        border-radius: 14px;
        color: white;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }
</style>

<div class="form-shell">
    <div>
        <h2 style="margin: 0 0 0.35rem; color: var(--primary-dark);">Faire un transfert</h2>
        <p style="margin: 0; color: var(--text-muted);">Le numéro actif est automatiquement utilisé comme source.</p>
    </div>

    <?php if (!empty($numeroActif)): ?>
        <div class="mini-card">
            <div class="label">Numéro source actif</div>
            <div class="value"><?= esc($numeroActif['prefixe']) ?> <?= esc($numeroActif['numero']) ?></div>
            <div style="margin-top: 0.35rem; color: var(--text-muted);">Solde: <?= number_format((float) ($numeroActif['solde'] ?? 0), 2, ',', ' ') ?> Ar</div>
        </div>

        <form action="<?= base_url('client-office/transfert/traiter') ?>" method="post" class="mini-card">
            <?= csrf_field() ?>
            <input type="hidden" name="numero_source" value="<?= esc($numeroActif['numero']) ?>">

            <div id="transfert-multiple-items">
                <div class="field" style="margin-bottom: 1rem;">
                    <label for="destinations-0">Numéro du destinataire</label>
                    <input type="text" name="destinations[]" id="destinations-0" placeholder="Ex: 1234567" required>
                </div>

                <div class="field" style="margin-bottom: 1rem;">
                    <label for="montants-0">Montant (Ar)</label>
                    <input type="number" name="montants[]" id="montants-0" min="1" step="1" required>
                </div>
            </div>

            <button type="button" class="submit-btn" id="add-transfer-line" style="background: #4d7cfe;">Ajouter un autre destinataire</button>

            <div class="field mb-3" style="margin-top: 1rem;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="include_withdrawal_fee" id="include_withdrawal_fee">
                    <label class="form-check-label" for="include_withdrawal_fee">Inclure les frais de retrait si le destinataire est d'un autre opérateur</label>
                </div>
                <div class="form-text">Le montant total débité inclura les frais de transfert et, si nécessaire, les frais de retrait.</div>
            </div>

            <div class="field">
                <label for="reference">Référence du transfert</label>
                <input type="text" name="reference" id="reference" placeholder="Raison du transfert (optionnel)">
            </div>

            <div style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Effectuer le transfert</button>
                <a href="<?= base_url('client-office') ?>" class="pill pill-neutral"><i class="fas fa-arrow-left"></i> Annuler</a>
            </div>
        </form>

        <script>
            document.getElementById('add-transfer-line').addEventListener('click', function () {
                const container = document.getElementById('transfert-multiple-items');
                const index = container.querySelectorAll('[name="destinations[]"]').length;

                const destinationField = document.createElement('div');
                destinationField.className = 'field';
                destinationField.style.marginBottom = '1rem';
                destinationField.innerHTML = `
                    <label for="destinations-${index}">Numéro du destinataire</label>
                    <input type="text" name="destinations[]" id="destinations-${index}" placeholder="Ex: 0331234567" required>
                `;

                const montantField = document.createElement('div');
                montantField.className = 'field';
                montantField.style.marginBottom = '1rem';
                montantField.innerHTML = `
                    <label for="montants-${index}">Montant (Ar)</label>
                    <input type="number" name="montants[]" id="montants-${index}" min="1" step="1" required>
                `;

                container.appendChild(destinationField);
                container.appendChild(montantField);
            });
        </script>
    <?php else: ?>
        <div class="notice">Aucun numéro n’est disponible pour effectuer un transfert.</div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>