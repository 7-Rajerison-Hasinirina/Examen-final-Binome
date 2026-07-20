<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace Opérateur') ?> | Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <style>
        :root {
            --primary-dark: #102a43;
            --primary: #1f5aa6;
            --primary-light: #2f7ae5;
            --accent: #00c2ff;
            --bg: #eef5ff;
            --panel: #ffffff;
            --text: #17324d;
            --text-muted: #63758a;
            --success: #0b9b63;
            --warning: #d97706;
            --danger: #d64545;
            --shadow: 0 18px 45px rgba(18, 36, 68, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            scroll-behavior: smooth;
            background:
                radial-gradient(circle at top left, rgba(0, 194, 255, 0.12), transparent 28%),
                radial-gradient(circle at top right, rgba(31, 90, 166, 0.12), transparent 30%),
                linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            color: inherit;
        }

        .operator-layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
        }

        .operator-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            padding: 1.2rem;
            background: linear-gradient(180deg, var(--primary-dark), #0c2035 100%);
            color: white;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-card {
            padding: 1rem;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.10);
        }

        .brand-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .brand-subtitle {
            margin: 0.25rem 0 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .sidebar-section {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-label {
            margin-bottom: 0.7rem;
            font-size: 0.76rem;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.62);
        }

        .sidebar-value {
            font-size: 1rem;
            font-weight: 800;
            color: white;
        }

        .sidebar-meta {
            margin-top: 0.35rem;
            color: rgba(255, 255, 255, 0.74);
            font-size: 0.88rem;
        }

        .sidebar-links {
            display: grid;
            gap: 0.7rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.7rem;
            padding: 0.9rem 1rem;
            border-radius: 14px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.92);
            background: rgba(255, 255, 255, 0.05);
            transition: transform 0.18s ease, background 0.18s ease;
        }

        .sidebar-link:hover {
            transform: translateX(3px);
            background: rgba(0, 194, 255, 0.14);
            color: white;
            text-decoration: none;
        }

        .sidebar-link strong {
            font-size: 0.94rem;
        }

        .sidebar-link span {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.68);
        }

        .operator-main {
            min-width: 0;
            display: grid;
            grid-template-rows: auto 1fr auto;
        }

        .operator-topbar {
            position: sticky;
            top: 0;
            z-index: 15;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.4rem;
            background: rgba(248, 251, 255, 0.88);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(31, 90, 166, 0.10);
        }

        .topbar-copy strong {
            display: block;
            color: var(--primary-dark);
            font-size: 1rem;
        }

        .topbar-copy span {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .pill-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.72rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .pill-neutral {
            background: white;
            color: var(--primary-dark);
            border-color: rgba(31, 90, 166, 0.16);
        }

        .pill-danger {
            background: linear-gradient(135deg, #e05b5b, #c43e3e);
            color: white;
        }

        .content-frame {
            padding: 1.4rem;
        }

        .flash-stack {
            display: grid;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        .flash-box {
            padding: 1rem 1.05rem;
            border-radius: 16px;
            border: 1px solid;
            font-weight: 600;
        }

        .flash-success {
            background: rgba(11, 155, 99, 0.10);
            border-color: rgba(11, 155, 99, 0.22);
            color: #0b7d51;
        }

        .flash-error {
            background: rgba(214, 69, 69, 0.10);
            border-color: rgba(214, 69, 69, 0.22);
            color: #b03f3f;
        }

        .operator-footer {
            padding: 0 1.4rem 1.4rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .hero-panel {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(260px, 0.8fr);
            gap: 1rem;
            padding: 1.4rem;
            border-radius: 26px;
            background: linear-gradient(135deg, rgba(31, 90, 166, 0.96), rgba(16, 42, 67, 0.96));
            color: white;
            box-shadow: var(--shadow);
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 0.32rem 0.7rem;
            margin-bottom: 0.8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.86);
        }

        .hero-panel h2 {
            margin: 0 0 0.5rem;
            font-size: clamp(1.6rem, 2vw, 2.25rem);
        }

        .hero-panel p {
            margin: 0;
            line-height: 1.65;
            color: rgba(255, 255, 255, 0.84);
        }

        .hero-badges {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .hero-summary {
            padding: 1rem 1.05rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(10px);
            align-self: stretch;
            display: grid;
            gap: 0.45rem;
        }

        .summary-label {
            font-size: 0.76rem;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.68);
        }

        .summary-name {
            font-size: 1.15rem;
            font-weight: 800;
            color: white;
        }

        .summary-line {
            color: rgba(255, 255, 255, 0.78);
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.32rem 0.68rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .tag-success { background: rgba(11, 155, 99, 0.12); color: var(--success); }
        .tag-warning { background: rgba(217, 119, 6, 0.12); color: var(--warning); }
        .tag-neutral { background: rgba(31, 90, 166, 0.10); color: var(--primary); }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-card,
        .panel {
            background: var(--panel);
            border: 1px solid rgba(31, 90, 166, 0.08);
            border-radius: 22px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .stat-card {
            padding: 1rem 1.05rem;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .stat-value {
            margin-top: 0.25rem;
            font-size: 1.55rem;
            font-weight: 900;
            color: var(--primary-dark);
        }

        .stat-note {
            margin-top: 0.35rem;
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.05rem;
            border-bottom: 1px solid rgba(31, 90, 166, 0.08);
        }

        .panel-head h3 {
            margin: 0;
            font-size: 1.05rem;
            color: var(--primary-dark);
        }

        .panel-head span {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .panel-body {
            padding: 1rem 1.05rem 1.05rem;
        }

        .mini-grid {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .mini-card {
            padding: 0.95rem 1rem;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            border: 1px solid rgba(31, 90, 166, 0.10);
        }

        .mini-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.45rem;
        }

        .mini-head strong {
            color: var(--primary-dark);
        }

        .mini-meta {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 0.9rem 1rem;
            background: #eef5ff;
            color: var(--primary-dark);
            border-bottom: 1px solid rgba(31, 90, 166, 0.10);
            white-space: nowrap;
        }

        tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid rgba(31, 90, 166, 0.08);
            white-space: nowrap;
        }

        tbody tr:hover {
            background: rgba(0, 194, 255, 0.03);
        }

        .empty-state {
            padding: 1rem 0;
            color: var(--text-muted);
        }

        .shortcut-stack {
            display: grid;
            gap: 0.75rem;
        }

        .shortcut-link {
            display: grid;
            gap: 0.2rem;
            padding: 0.95rem 1rem;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            border: 1px solid rgba(31, 90, 166, 0.10);
            text-decoration: none;
        }

        .shortcut-link strong {
            color: var(--primary-dark);
        }

        .shortcut-link span {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .shortcut-danger {
            border-color: rgba(214, 69, 69, 0.16);
        }

        .shortcut-note {
            margin-top: 1rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        @media (max-width: 980px) {
            .operator-layout {
                grid-template-columns: 1fr;
            }

            .operator-sidebar {
                position: relative;
                height: auto;
            }

            .hero-panel,
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .operator-topbar,
            .content-frame,
            .operator-footer {
                padding-left: 0.85rem;
                padding-right: 0.85rem;
            }

            .operator-topbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php
    $flashSuccess = session()->getFlashdata('success');
    $flashError = session()->getFlashdata('error');
    $currentRoute = service('uri')->getPath();
    ?>
    <div class="operator-layout">
        <aside class="operator-sidebar">
            <div class="brand-card">
                <h1 class="brand-title">Mobile Money</h1>
                <p class="brand-subtitle">Console d’administration opérateur, structurée pour le suivi des comptes et de l’activité.</p>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-label">Session active</div>
                <div class="sidebar-value"><?= esc($nom ?? 'Opérateur') ?></div>
                <div class="sidebar-meta">Numéro: <?= esc($numero ?? '-') ?></div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-label">Navigation</div>
                <nav class="sidebar-links">
                    <a class="sidebar-link" href="<?= base_url('operateur-office') ?>">
                        <strong>Tableau de bord</strong>
                        <span><?= str_contains($currentRoute, 'operateur-office') ? 'Actif' : 'Ouvrir' ?></span>
                    </a>
                    <a class="sidebar-link" href="<?= base_url('operateur-office') ?>#prefixes">
                        <strong>Configuration des préfixes</strong>
                        <span>Ajouter / lister</span>
                    </a>
                    <a class="sidebar-link" href="<?= base_url('operateur-office') ?>#types">
                        <strong>Création des types d'opérations</strong>
                        <span>Ajouter un type</span>
                    </a>
                    <a class="sidebar-link" href="<?= base_url('operateur-office') ?>#gains">
                        <strong>Situation de gain</strong>
                        <span>Entrées / sorties</span>
                    </a>
                    <a class="sidebar-link" href="<?= base_url('operateur-office') ?>#comptes">
                        <strong>Situation des comptes</strong>
                        <span>Clients / opérateurs</span>
                    </a>
                    <a class="sidebar-link" href="<?= base_url('operateur-office/logout') ?>">
                        <strong>Déconnexion</strong>
                        <span>Quitter la session</span>
                    </a>
                </nav>
            </div>
        </aside>

        <div class="operator-main">
            <header class="operator-topbar">
                <div class="topbar-copy">
                    <strong><?= esc($title ?? 'Espace Opérateur') ?></strong>
                    <span>Gestion des préfixes, comptes et opérations</span>
                </div>

                <div class="topbar-actions">
                    <a href="<?= base_url('operateur-office') ?>" class="pill-btn pill-neutral">Rafraîchir</a>
                    <a href="<?= base_url('operateur-office/logout') ?>" class="pill-btn pill-danger">Se déconnecter</a>
                </div>
            </header>

            <main class="content-frame">
                <div class="flash-stack">
                    <?php if ($flashSuccess): ?>
                        <div class="flash-box flash-success"><?= esc((string) $flashSuccess) ?></div>
                    <?php endif; ?>

                    <?php if ($flashError): ?>
                        <div class="flash-box flash-error"><?= esc((string) $flashError) ?></div>
                    <?php endif; ?>
                </div>

                <?= $this->renderSection('content') ?>
            </main>

            <footer class="operator-footer">
                Mobile Money - Espace opérateur sécurisé
            </footer>
        </div>
    </div>
</body>
</html>
