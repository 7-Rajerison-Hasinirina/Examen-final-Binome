<?= $this->extend('client/layout') ?>

<?= $this->section('content') ?>
<style>
    .dashboard-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .dashboard-title h2 {
        margin: 0;
        font-size: 1.7rem;
        color: var(--primary-dark);
    }

    .dashboard-title p {
        margin: 0.35rem 0 0;
        color: var(--text-muted);
    }

    .active-card {
        display: grid;
        gap: 0.25rem;
        min-width: 240px;
        padding: 1rem 1.1rem;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.13), rgba(36, 74, 134, 0.09));
        border: 1px solid rgba(36, 74, 134, 0.12);
    }

    .active-card .label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
    }

    .active-card .value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--primary-dark);
    }

    .active-card .balance {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--success);
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-top: 1.25rem;
    }

    .action-card {
        display: grid;
        gap: 0.8rem;
        padding: 1.1rem;
        border-radius: 18px;
        border: 1px solid rgba(36, 74, 134, 0.12);
        background: linear-gradient(180deg, #ffffff 0%, #f6faff 100%);
        text-decoration: none;
        color: var(--text);
        box-shadow: 0 10px 25px rgba(18, 36, 68, 0.06);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 34px rgba(18, 36, 68, 0.10);
        text-decoration: none;
        color: var(--text);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        color: white;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .action-title {
        font-weight: 800;
        color: var(--primary-dark);
    }

    .action-description {
        color: var(--text-muted);
        font-size: 0.92rem;
    }
</style>

<div class="dashboard-head">
    <div class="dashboard-title">
        <h2>Tableau de bord client</h2>
        <p>Les actions s’appliquent uniquement au numéro actif sélectionné dans le layout.</p>
    </div>

    <?php if (!empty($numeroActif)): ?>
        <div class="active-card">
            <div class="label">Numéro actif</div>
            <div class="value"><?= esc($numeroActif['prefixe']) ?> <?= esc($numeroActif['numero']) ?></div>
            <div class="balance"><?= number_format((float) ($soldeActif ?? 0), 2, ',', ' ') ?> Ar</div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">
                <?= esc($numeroActif['operateur'] ?? '') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="action-grid">
    <a href="<?= base_url('client-office/solde') ?>" class="action-card">
        <div class="action-icon"><i class="fas fa-wallet"></i></div>
        <div class="action-title">Voir le solde</div>
        <div class="action-description">Consulter le solde du numéro actif.</div>
    </a>

    <a href="<?= base_url('client-office/depot') ?>" class="action-card">
        <div class="action-icon"><i class="fas fa-circle-plus"></i></div>
        <div class="action-title">Faire un dépôt</div>
        <div class="action-description">Créditer uniquement le numéro actif.</div>
    </a>

    <a href="<?= base_url('client-office/retrait') ?>" class="action-card">
        <div class="action-icon"><i class="fas fa-money-bill-wave"></i></div>
        <div class="action-title">Faire un retrait</div>
        <div class="action-description">Débiter uniquement le numéro actif.</div>
    </a>

    <a href="<?= base_url('client-office/transfert') ?>" class="action-card">
        <div class="action-icon"><i class="fas fa-right-left"></i></div>
        <div class="action-title">Faire un transfert</div>
        <div class="action-description">Transférer depuis le numéro actif.</div>
    </a>

    <a href="<?= base_url('client-office/historique') ?>" class="action-card">
        <div class="action-icon"><i class="fas fa-clock-rotate-left"></i></div>
        <div class="action-title">Historique</div>
        <div class="action-description">Voir les opérations du numéro actif.</div>
    </a>
</div>
<?= $this->endSection() ?>
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

        .balance-card {
            margin-top: 1rem;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.12) 0%, rgba(44, 90, 160, 0.10) 100%);
            border: 1px solid rgba(0, 212, 255, 0.25);
        }

        .balance-value {
            color: #1a8fb5;
            font-weight: 700;
            font-size: 1.15rem;
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
    <?php
    $flashSuccess = session()->getFlashdata('success');
    $flashError = session()->getFlashdata('error');
    ?>

    
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
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-lg-3">
                <div class="sidebar">
                    
                    <div class="sidebar-title">
                        <i class="fas fa-user"></i> Vos Informations
                    </div>
                    <div class="info-card">
                        <div class="info-item">
                            <span class="info-label">Nom</span>
                            <span class="info-value"><?= esc($nom) ?></span>
                        </div>
                    </div>

                    
                    <div class="sidebar-title" style="margin-top: 2rem;">
                        <i class="fas fa-phone"></i> Vos Numéros
                    </div>
                    <?php if (!empty($numeros)): ?>
                        <?php foreach($numeros as $numero): ?>
                            <div class="numero-item">
                                <strong><?= esc($numero['prefixe']) ?></strong> 
                                <span><?= esc($numero['numero']) ?></span>
                                <div style="margin-top: 0.35rem; font-size: 0.85rem; color: #666666;">
                                    Solde : <strong style="color: #1a8fb5;"><?= number_format((float) ($numero['solde'] ?? 0), 2, ',', ' ') ?> Ar</strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted" style="font-size: 0.9rem;">Aucun numéro enregistré</div>
                    <?php endif; ?>

                    
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

            
            <div class="col-lg-9">
                <div class="main-content">
                    
                    <?php if ($flashSuccess): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <?= esc((string) $flashSuccess) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashError): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= esc((string) $flashError) ?>
                        </div>
                    <?php endif; ?>

                    
                    <div class="page-title">
                        <i class="fas fa-chart-line"></i> Tableau de Bord Client
                    </div>

                    
                    <div class="action-grid">
                        
                        <a href="<?= base_url('client-office/depot') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-circle-plus"></i>
                            </div>
                            <div class="action-title">Faire un Dépôt</div>
                            <div class="action-description">Créditez votre compte</div>
                        </a>

                        
                        <a href="<?= base_url('client-office/solde') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="action-title">Voir le Solde</div>
                            <div class="action-description">Consultez votre solde actuel</div>
                        </a>

                        
                        <a href="<?= base_url('client-office/retrait') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="action-title">Faire un Retrait</div>
                            <div class="action-description">Retirez de l'argent</div>
                        </a>

                        
                        <a href="<?= base_url('client-office/transfert') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="action-title">Faire un Transfert</div>
                            <div class="action-description">Transférez de l'argent</div>
                        </a>

                        
                        <a href="<?= base_url('client-office/historique') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="action-title">Historique</div>
                            <div class="action-description">Consultez vos opérations</div>
                        </a>


                        <a href="<?= base_url('/client-office/pourcentage') ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="action-title">Epargne</div>
                            <div class="action-description">Pourcentage d'épargne </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
