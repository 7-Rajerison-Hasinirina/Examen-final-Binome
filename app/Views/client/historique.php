<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Historique') ?> | Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #1a3a52;
            --primary: #2c5aa0;
            --accent: #00d4ff;
            --bg: #0f2438;
            --bg-light: #1a3a52;
            --text: #ffffff;
            --text-muted: #b0bec5;
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
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent) !important;
        }

        .page-header {
            padding: 2rem;
            background: #e3f2fd;
            border-bottom: 2px solid var(--primary);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            color: #666666;
            font-size: 0.95rem;
            margin-top: 0.5rem;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #e3f2fd;
            border-color: #90caf9;
            color: var(--primary);
            font-weight: 600;
            border-top: none;
        }

        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1);
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 212, 255, 0.05);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .badge-pending {
            background: rgba(255, 165, 0, 0.2);
            color: #ffa500;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-completed {
            background: rgba(0, 208, 132, 0.2);
            color: #00d084;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-failed {
            background: rgba(255, 107, 107, 0.2);
            color: #ff6b6b;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-back {
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 90, 160, 0.3);
            text-decoration: none;
            color: white;
        }

        .montant-depot {
            color: #00d084;
            font-weight: 600;
        }

        .montant-retrait {
            color: #ff6b6b;
            font-weight: 600;
        }

        .montant-transfert {
            color: var(--accent);
            font-weight: 600;
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
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-history"></i> <?= esc($title) ?>
        </div>
        <div class="page-subtitle">Consultez vos opérations passées</div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content-card">
                    <?php if (empty($operations)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="empty-text">
                                Aucune opération enregistrée pour le moment
                            </div>
                            <p class="text-muted" style="margin-top: 1rem;">
                                Vos opérations apparaîtront ici une fois que vous en aurez effectuées.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-calendar"></i> Date</th>
                                        <th><i class="fas fa-exchange-alt"></i> Type</th>
                                        <th><i class="fas fa-phone"></i> Numéro</th>
                                        <th><i class="fas fa-money-bill"></i> Montant</th>
                                        <th><i class="fas fa-info-circle"></i> Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($operations as $op): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($op['date'])) ?></td>
                                            <td><?= esc($op['type']) ?></td>
                                            <td><?= esc($op['numero']) ?></td>
                                            <td>
                                                <span class="montant-<?= strtolower($op['type']) ?>">
                                                    <?= number_format($op['montant'], 2, ',', ' ') ?> Ar
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-completed">
                                                    <i class="fas fa-check-circle"></i> Effectuée
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 2rem; text-align: center;">
                        <a href="<?= base_url('client-office') ?>" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Retour au Tableau de Bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
