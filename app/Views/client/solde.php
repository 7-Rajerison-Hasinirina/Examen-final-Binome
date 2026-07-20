<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Solde') ?> | Mobile Money</title>
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
            --success: #00d084;
        }

        body {
            background: linear-gradient(135deg, var(--bg) 0%, var(--bg-light) 100%);
            color: var(--text);
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
            background: linear-gradient(135deg, rgba(44, 90, 160, 0.2) 0%, rgba(0, 212, 255, 0.1) 100%);
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 0.5rem;
        }

        .content-card {
            background: rgba(42, 90, 160, 0.15);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .solde-display {
            text-align: center;
            padding: 2rem;
        }

        .solde-label {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .solde-value {
            font-size: 3rem;
            font-weight: 700;
            color: var(--success);
            margin-bottom: 1rem;
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
            <i class="fas fa-wallet"></i> <?= esc($title) ?>
        </div>
        <div class="page-subtitle">Consultez votre solde actuel</div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="content-card">
                    <div class="solde-display">
                        <div class="solde-label">Solde Actuel</div>
                        <div class="solde-value">0.00 Ar</div>
                        <p class="text-muted">Fonctionnalité en cours de développement</p>
                    </div>

                    <div style="text-align: center; margin-top: 2rem;">
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
