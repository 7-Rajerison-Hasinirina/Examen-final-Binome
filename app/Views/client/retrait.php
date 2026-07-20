<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<style>
    .form-shell {
        max-width: 760px;
        display: grid;
        gap: 1rem;
    }

    .notice {
        padding: 1rem 1.1rem;
        border-radius: 16px;
        background: rgba(217, 119, 6, 0.09);
        border: 1px solid rgba(217, 119, 6, 0.18);
        color: #9a5a00;
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

    .form-row-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
        background: linear-gradient(135deg, var(--warning), #ff8c1a);
    }
</style>

<div class="form-shell">
    <div>
        <h2 style="margin: 0 0 0.35rem; color: var(--primary-dark);">Faire un retrait</h2>
        <p style="margin: 0; color: var(--text-muted);">Le retrait sera appliqué uniquement au numéro actif sélectionné.</p>
    </div>

    <?php if (!empty($numeroActif)): ?>
        <div class="mini-card">
            <div class="label">Numéro actif</div>
            <div class="value"><?= esc($numeroActif['prefixe']) ?> <?= esc($numeroActif['numero']) ?></div>
            <div style="margin-top: 0.35rem; color: var(--text-muted);">Solde: <?= number_format((float) ($numeroActif['solde'] ?? 0), 2, ',', ' ') ?> Ar</div>
        </div>

        <div class="notice">Vous n’avez plus besoin de choisir un numéro ici. Le layout utilise le numéro actif.</div>

        <form action="<?= base_url('client-office/retrait/traiter') ?>" method="post" class="mini-card">
            <?= csrf_field() ?>
            <input type="hidden" name="numero" value="<?= esc($numeroActif['numero']) ?>">

            <div class="form-row-grid">
                <div class="field">
                    <label for="montant">Montant (Ar)</label>
                    <input type="number" name="montant" id="montant" min="1" step="1" required>
                </div>

                <div class="field">
                    <label for="raison">Raison du retrait</label>
                    <textarea name="raison" id="raison" rows="3" placeholder="Motif du retrait"></textarea>
                </div>
            </div>

            <div style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <button type="submit" class="submit-btn"><i class="fas fa-check-circle"></i> Confirmer le retrait</button>
                <a href="<?= base_url('client-office') ?>" class="pill pill-neutral"><i class="fas fa-arrow-left"></i> Annuler</a>
            </div>
        </form>
    <?php else: ?>
        <div class="notice">Aucun numéro n’est disponible pour effectuer un retrait.</div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>