<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Retrait') ?> | Mobile Money</title>
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
            --warning: #ffa500;
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

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.3);
            color: var(--text);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--accent);
            color: var(--text);
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .btn-submit {
            background: linear-gradient(90deg, var(--warning) 0%, #ffb700 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(90deg, #ff9600 0%, #ff8800 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3);
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

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-group .btn-back {
            flex: 1;
            text-align: center;
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
            <i class="fas fa-money-bill-wave"></i> <?= esc($title) ?>
        </div>
        <div class="page-subtitle">Effectuez un retrait d'argent</div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="content-card">
                    <form action="<?= base_url('client-office/retrait/traiter') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="numero" class="form-label">
                                <i class="fas fa-phone"></i> Numéro à débiter
                            </label>
                            <select name="numero" id="numero" class="form-control" required>
                                <option value="">Sélectionner un numéro</option>
                                <?php if (!empty($numeros)): ?>
                                    <?php foreach($numeros as $num): ?>
                                        <option value="<?= esc($num['numero']) ?>">
                                            <?= esc($num['prefixe']) ?> - <?= esc($num['numero']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="montant" class="form-label">
                                <i class="fas fa-money-bill"></i> Montant (Ar)
                            </label>
                            <input type="number" name="montant" id="montant" class="form-control" 
                                   placeholder="Ex: 10000" step="1" min="1" required>
                        </div>

                        <div class="form-group">
                            <label for="raison" class="form-label">
                                <i class="fas fa-comment"></i> Raison du retrait
                            </label>
                            <textarea name="raison" id="raison" class="form-control" 
                                     placeholder="Motif du retrait" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-check-circle"></i> Confirmer le Retrait
                        </button>

                        <div class="btn-group">
                            <a href="<?= base_url('client-office') ?>" class="btn-back">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
