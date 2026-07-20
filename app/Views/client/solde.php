<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<style>
    .hero-title {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }

    .hero-title h2 {
        margin: 0;
        color: var(--primary-dark);
    }

    .balance-card {
        display: grid;
        gap: 0.55rem;
        padding: 1.25rem;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.10), rgba(36, 74, 134, 0.08));
        border: 1px solid rgba(36, 74, 134, 0.12);
        max-width: 620px;
    }

    .balance-card .operateur {
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.78rem;
    }

    .balance-card .numero {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--primary-dark);
    }

    .balance-card .solde {
        font-size: 2rem;
        font-weight: 900;
        color: var(--success);
    }

    .meta {
        color: var(--text-muted);
        margin-top: 0.25rem;
    }
</style>

<div class="hero-title">
    <i class="fas fa-wallet" style="font-size: 1.7rem; color: var(--primary);"></i>
    <h2>Solde du numéro actif</h2>
</div>

<?php if (!empty($numeroActif)): ?>
    <div class="balance-card">
        <div class="operateur"><?= esc($numeroActif['operateur'] ?? 'Numéro') ?></div>
        <div class="numero"><?= esc($numeroActif['prefixe']) ?> <?= esc($numeroActif['numero']) ?></div>
        <div class="solde"><?= number_format((float) ($numeroActif['solde'] ?? 0), 2, ',', ' ') ?> Ar</div>
        <div class="meta">Le solde est calculé uniquement pour ce numéro.</div>
    </div>
<?php else: ?>
    <div class="text-muted">Aucun numéro n’est rattaché à ce compte.</div>
<?php endif; ?>

<div style="margin-top: 1.25rem;">
    <a href="<?= base_url('client-office') ?>" class="pill pill-neutral">
        <i class="fas fa-arrow-left"></i> Retour au tableau de bord
    </a>
</div>
<?= $this->endSection() ?>