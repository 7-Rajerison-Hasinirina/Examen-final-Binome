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
            --primary-dark: #1a3a52;
            --primary: #2c5aa0;
            --primary-light: #4a7bc9;
            --accent: #00d4ff;
            --bg: #0f2438;
            --bg-light: #1a3a52;
            --text: #ffffff;
            --text-muted: #b0bec5;
            --success: #00d084;
            --danger: #ff6b6b;
            --warning: #ffa500;
        }

        body {
            background: #ffffff;
            color: #333333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(90deg, var(--primary-dark) 0%, var(--primary) 100%);
            border-bottom: 3px solid var(--accent);
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent) !important;
            letter-spacing: 0.5px;
        }

        .navbar-brand i {
            margin-right: 0.5rem;
        }

        .user-info {
            color: #ffffff;
            font-size: 0.95rem;
        }

        .user-info strong {
            color: var(--accent);
            font-size: 1.1rem;
        }

        .sidebar {
            background: #f8f9fa;
            border-right: 1px solid #e0e0e0;
            padding: 2rem 1rem;
            position: sticky;
            top: 70px;
            height: calc(100vh - 70px);
            overflow-y: auto;
        }

        .sidebar-title {
            color: #2c5aa0;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #2c5aa0;
        }

        .info-card {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(44, 90, 160, 0.1);
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #666666;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .info-value {
            color: var(--accent);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .main-content {
            padding: 2rem;
            background: #ffffff;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c5aa0;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: #ffffff;
            border: 2px solid #e0e0e0;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .action-card:hover {
            background: #f0f7ff;
            border-color: #2c5aa0;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(44, 90, 160, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .action-icon {
            font-size: 2.5rem;
            color: var(--accent);
        }

        .action-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c5aa0;
        }

        .action-description {
            font-size: 0.85rem;
            color: #666666;
        }

        .alert {
            border-radius: 0.75rem;
            border: 1px solid;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background-color: rgba(0, 208, 132, 0.15);
            border-color: var(--success);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(255, 107, 107, 0.15);
            border-color: var(--danger);
            color: var(--danger);
        }

        .btn-logout {
            background: linear-gradient(90deg, var(--danger) 0%, #ff8a7b 100%);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: linear-gradient(90deg, #ff6b6b 0%, #ff5252 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
            text-decoration: none;
            color: white;
        }

        .numero-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            color: #333333;
        }

        .numero-item span {
            color: var(--accent);
            font-weight: 600;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 212, 255, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 212, 255, 0.5);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                height: auto;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .page-title {
                font-size: 1.5rem;
            }

            .action-grid {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-coins"></i> Mobile Money
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="user-info">
                    <i class="fas fa-user-circle" style="color: var(--accent); font-size: 1.5rem;"></i>
                    <span>Bienvenue, <strong><?= esc($nom) ?></strong></span>
                </div>
                <a href="<?= base_url('client-office/logout') ?>" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnecter
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar">
                    <!-- Informations Utilisateur -->
                    <div class="sidebar-title">
                        <i class="fas fa-user"></i> Vos Informations
                    </div>
                    <div class="info-card">
                        <div class="info-item">
                            <span class="info-label">Nom</span>
                            <span class="info-value"><?= esc($nom) ?></span>
                        </div>
                    </div>

                    <!-- Vos Numéros -->
                    <div class="sidebar-title" style="margin-top: 2rem;">
                        <i class="fas fa-phone"></i> Vos Numéros
                    </div>
                    <?php if (!empty($numeros)): ?>
                        <?php foreach($numeros as $numero): ?>
                            <div class="numero-item">
                                <strong><?= esc($numero['prefixe']) ?></strong> 
                                <span><?= esc($numero['numero']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted" style="font-size: 0.9rem;">Aucun numéro enregistré</div>
                    <?php endif; ?>

                    <!-- Types d'Opération -->
                    <div class="sidebar-title" style="margin-top: 2rem;">
                        <i class="fas fa-exchange-alt"></i> Opérations Disponibles
                    </div>
                    <?php if (!empty($operations)): ?>
                        <?php foreach($operations as $op): ?>
                            <div class="numero-item">
                                <i class="fas fa-check-circle"></i> <?= esc($op['libelle']) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="main-content">
                    <!-- Messages Flash -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Page Title -->
                    <div class="page-title">
                        <i class="fas fa-chart-line"></i> Tableau de Bord Client
                    </div>

                    <!-- Action Cards -->
                    <div class="action-grid">
                        <!-- Voir Solde -->
                        <a href="<?= base_url('client-office/solde') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="action-title">Voir le Solde</div>
                            <div class="action-description">Consultez votre solde actuel</div>
                        </a>

                        <!-- Retrait -->
                        <a href="<?= base_url('client-office/retrait') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="action-title">Faire un Retrait</div>
                            <div class="action-description">Retirez de l'argent</div>
                        </a>

                        <!-- Transfert -->
                        <a href="<?= base_url('client-office/transfert') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="action-title">Faire un Transfert</div>
                            <div class="action-description">Transférez de l'argent</div>
                        </a>

                        <!-- Historique -->
                        <a href="<?= base_url('client-office/historique') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="action-title">Historique</div>
                            <div class="action-description">Consultez vos opérations</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
