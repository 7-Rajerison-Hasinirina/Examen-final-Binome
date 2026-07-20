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
            --text: #333333;
            --text-light: #666666;
            --border: #e0e0e0;
            --warning: #ffa500;
        }

        body {
            background: #ffffff;
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
            background: #fff3cd;
            border-bottom: 2px solid var(--warning);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--warning);
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-top: 0.5rem;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: var(--primary);
            color: var(--text);
            box-shadow: 0 0 10px rgba(44, 90, 160, 0.2);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--warning) 0%, #ff9500 100%);
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
            background: linear-gradient(135deg, #ff9500 0%, #ff8000 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3);
        }

        .btn-back {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
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
