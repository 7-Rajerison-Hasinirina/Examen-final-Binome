<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace Client') ?> | Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #162b4d;
            --primary: #244a86;
            --primary-light: #3d6bc7;
            --accent: #00d4ff;
            --bg: #f4f8ff;
            --panel: #ffffff;
            --text: #19324f;
            --text-muted: #6b7c93;
            --success: #00a86b;
            --danger: #e05656;
            --warning: #d97706;
            --shadow: 0 18px 45px rgba(18, 36, 68, 0.10);
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(0, 212, 255, 0.10), transparent 28%),
                radial-gradient(circle at top right, rgba(36, 74, 134, 0.10), transparent 30%),
                var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .client-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 320px 1fr;
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background: linear-gradient(180deg, var(--primary-dark) 0%, #10203d 100%);
            color: white;
            padding: 1.5rem;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.25rem 0 1.25rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.10);
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), #52a7ff);
            display: grid;
            place-items: center;
            color: #0d2038;
            box-shadow: 0 12px 26px rgba(0, 212, 255, 0.35);
        }

        .brand h1 {
            font-size: 1.15rem;
            line-height: 1.1;
            margin: 0;
        }

        .brand p {
            margin: 0.25rem 0 0;
            font-size: 0.86rem;
            color: rgba(255, 255, 255, 0.72);
        }

        .sidebar-block {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 1rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
        }

        .sidebar-label {
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.62);
            margin-bottom: 0.6rem;
        }

        .active-numero {
            display: grid;
            gap: 0.35rem;
        }

        .active-numero .numero-line {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
        }

        .active-numero .numero-balance {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--accent);
        }

        .numero-switch {
            display: grid;
            gap: 0.75rem;
        }

        .numero-switch select,
        .numero-switch button {
            width: 100%;
            border-radius: 12px;
            border: none;
        }

        .numero-switch select {
            padding: 0.85rem 0.95rem;
            background: rgba(255, 255, 255, 0.92);
            color: var(--text);
        }

        .numero-switch button {
            padding: 0.85rem 1rem;
            background: linear-gradient(135deg, var(--accent), #6cbcff);
            color: #0f2540;
            font-weight: 700;
        }

        .nav-links {
            display: grid;
            gap: 0.6rem;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.9rem 1rem;
            border-radius: 14px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.90);
            background: rgba(255, 255, 255, 0.04);
            transition: transform 0.18s ease, background 0.18s ease;
        }

        .nav-link-item:hover {
            color: white;
            text-decoration: none;
            background: rgba(0, 212, 255, 0.14);
            transform: translateX(3px);
        }

        .nav-link-item.active {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.18), rgba(36, 74, 134, 0.30));
            border: 1px solid rgba(0, 212, 255, 0.18);
        }

        .main-panel {
            min-width: 0;
            display: grid;
            grid-template-rows: auto 1fr;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: rgba(244, 248, 255, 0.86);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(36, 74, 134, 0.12);
        }

        .topbar-title {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .topbar-title strong {
            font-size: 1rem;
            color: var(--primary-dark);
        }

        .topbar-title span {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.62rem 0.9rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .pill-neutral {
            background: white;
            color: var(--primary-dark);
            border: 1px solid rgba(36, 74, 134, 0.14);
        }

        .pill-danger {
            background: linear-gradient(135deg, #ff7d7d, #e04f4f);
            color: white;
        }

        .page-content {
            padding: 1.5rem;
        }

        .content-card {
            background: var(--panel);
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(36, 74, 134, 0.08);
            overflow: hidden;
        }

        .flash-wrap {
            display: grid;
            gap: 0.75rem;
            padding: 1.25rem 1.25rem 0;
        }

        .alert-box {
            border-radius: 14px;
            padding: 1rem 1.1rem;
            border: 1px solid;
            font-weight: 600;
        }

        .alert-success-box {
            background: rgba(0, 168, 107, 0.10);
            border-color: rgba(0, 168, 107, 0.24);
            color: #0f7a50;
        }

        .alert-error-box {
            background: rgba(224, 86, 86, 0.10);
            border-color: rgba(224, 86, 86, 0.24);
            color: #b53d3d;
        }

        .section-body {
            padding: 1.25rem;
        }

        .mobile-toggle {
            display: none;
        }

        @media (max-width: 992px) {
            .client-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                height: auto;
            }

            .mobile-toggle {
                display: inline-flex;
            }
        }

        @media (max-width: 640px) {
            .page-content {
                padding: 0.8rem;
            }

            .topbar {
                padding: 0.85rem;
            }

            .topbar-actions {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php
    $flashSuccess = session()->getFlashdata('success');
    $flashError = session()->getFlashdata('error');
    $currentRoute = service('uri')->getPath();
    $numeroActif = $numeroActif ?? null;
    $numeros = $numeros ?? [];
    ?>
    <div class="client-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark"><i class="fas fa-coins"></i></div>
                <div>
                    <h1>Mobile Money</h1>
                    <p>Espace client sécurisé</p>
                </div>
            </div>

            <div class="sidebar-block">
                <div class="sidebar-label">Numéro actif</div>
                <?php if ($numeroActif): ?>
                    <div class="active-numero">
                        <div class="numero-line"><?= esc($numeroActif['operateur'] ?? 'Compte client') ?></div>
                        <div><?= esc($numeroActif['prefixe']) ?> <?= esc($numeroActif['numero']) ?></div>
                        <div class="numero-balance"><?= number_format((float) ($numeroActif['solde'] ?? 0), 2, ',', ' ') ?> Ar</div>
                    </div>
                <?php else: ?>
                    <div style="color: rgba(255,255,255,0.8);">Aucun numéro disponible.</div>
                <?php endif; ?>
            </div>

            <div class="sidebar-block">
                <div class="sidebar-label">Changer de numéro</div>
                <form action="<?= base_url('client-office/numero/activer') ?>" method="post" class="numero-switch">
                    <?= csrf_field() ?>
                    <select name="numero_id" required>
                        <option value="">Sélectionner un numéro</option>
                        <?php foreach ($numeros as $numero): ?>
                            <option value="<?= esc((string) $numero['id']) ?>" <?= $numeroActif && (int) $numeroActif['id'] === (int) $numero['id'] ? 'selected' : '' ?>>
                                <?= esc($numero['prefixe']) ?> <?= esc($numero['numero']) ?> - <?= number_format((float) ($numero['solde'] ?? 0), 2, ',', ' ') ?> Ar
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit"><i class="fas fa-sync-alt"></i> Activer</button>
                </form>
            </div>

            <div class="sidebar-block">
                <div class="sidebar-label">Navigation</div>
                <nav class="nav-links">
                    <a class="nav-link-item <?= str_contains($currentRoute, 'client-office') && !str_contains($currentRoute, 'solde') && !str_contains($currentRoute, 'depot') && !str_contains($currentRoute, 'retrait') && !str_contains($currentRoute, 'transfert') && !str_contains($currentRoute, 'historique') ? 'active' : '' ?>" href="<?= base_url('client-office') ?>">
                        <i class="fas fa-chart-line"></i> Tableau de bord
                    </a>
                    <a class="nav-link-item <?= str_contains($currentRoute, 'solde') ? 'active' : '' ?>" href="<?= base_url('client-office/solde') ?>">
                        <i class="fas fa-wallet"></i> Solde
                    </a>
                    <a class="nav-link-item <?= str_contains($currentRoute, 'depot') ? 'active' : '' ?>" href="<?= base_url('client-office/depot') ?>">
                        <i class="fas fa-circle-plus"></i> Dépôt
                    </a>
                    <a class="nav-link-item <?= str_contains($currentRoute, 'retrait') ? 'active' : '' ?>" href="<?= base_url('client-office/retrait') ?>">
                        <i class="fas fa-money-bill-wave"></i> Retrait
                    </a>
                    <a class="nav-link-item <?= str_contains($currentRoute, 'transfert') ? 'active' : '' ?>" href="<?= base_url('client-office/transfert') ?>">
                        <i class="fas fa-right-left"></i> Transfert
                    </a>
                    <a class="nav-link-item <?= str_contains($currentRoute, 'historique') ? 'active' : '' ?>" href="<?= base_url('client-office/historique') ?>">
                        <i class="fas fa-clock-rotate-left"></i> Historique
                    </a>
                    <a class="nav-link-item" href="<?= base_url('client-office/logout') ?>">
                        <i class="fas fa-right-from-bracket"></i> Déconnexion
                    </a>
                </nav>
            </div>
        </aside>

        <div class="main-panel">
            <header class="topbar">
                <div class="topbar-title">
                    <strong><?= esc($title ?? 'Espace Client') ?></strong>
                    <span>Utilisateur: <?= esc($nom ?? '') ?></span>
                </div>
                <div class="topbar-actions">
                    <?php if ($numeroActif): ?>
                        <div class="pill pill-neutral mobile-toggle">
                            <i class="fas fa-mobile-alt"></i>
                            <?= esc($numeroActif['prefixe']) ?> <?= esc($numeroActif['numero']) ?>
                        </div>
                    <?php endif; ?>
                    <a class="pill pill-neutral" href="<?= base_url('client-office/solde') ?>">
                        <i class="fas fa-wallet"></i> Voir le solde
                    </a>
                    <a class="pill pill-danger" href="<?= base_url('client-office/logout') ?>">
                        <i class="fas fa-right-from-bracket"></i> Déconnexion
                    </a>
                </div>
            </header>

            <main class="page-content">
                <div class="content-card">
                    <?php if ($flashSuccess): ?>
                        <div class="flash-wrap">
                            <div class="alert-box alert-success-box"><?= esc((string) $flashSuccess) ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($flashError): ?>
                        <div class="flash-wrap">
                            <div class="alert-box alert-error-box"><?= esc((string) $flashError) ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="section-body">
                        <?= $this->renderSection('content') ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
