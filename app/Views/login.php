<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Connexion') ?> | Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #1a3a52;
            --primary: #2c5aa0;
            --primary-light: #4a7bc9;
            --accent: #00d4ff;
            --text: #333333;
            --text-light: #666666;
            --border: #e0e0e0;
            --bg-light: #f8f9fa;
            --success: #00d084;
            --danger: #ff6b6b;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-shell {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(44, 90, 160, 0.12);
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            padding: 3rem 2rem;
            text-align: center;
            color: #ffffff;
            border-bottom: 3px solid var(--accent);
        }

        .login-header-icon {
            font-size: 3rem;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .login-header h1 {
            margin: 0 0 0.5rem;
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
        }

        .login-header p {
            margin: 0;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
        }

        .login-panel {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--text);
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            font-size: 1rem;
            color: var(--text);
            background: #ffffff;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-group input::placeholder {
            color: #aaaaaa;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        .form-actions {
            margin-top: 2rem;
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 90, 160, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .form-divider {
            display: flex;
            align-items: center;
            margin: 2rem 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .form-divider span {
            padding: 0 1rem;
            font-weight: 500;
        }

        .login-info {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-left: 4px solid var(--primary);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 2rem;
            color: var(--text);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .login-info i {
            color: var(--primary);
            margin-right: 0.5rem;
        }

        @media (max-width: 480px) {
            .login-shell {
                border-radius: 1rem;
            }

            .login-panel {
                padding: 1.5rem;
            }

            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .login-header-icon {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
<div class="login-shell">
    <!-- Header avec logo -->
    <div class="login-header">
        <div class="login-header-icon">
            <i class="fas fa-coins"></i>
        </div>
        <h1>Mobile Money</h1>
        <p>Système de Transfert Monétaire</p>
    </div>

    <!-- Formulaire de connexion -->
    <div class="login-panel">
        <form action="<?= base_url('login/valider') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Nom -->
            <div class="form-group">
                <label for="nom">
                    <i class="fas fa-user" style="color: var(--primary); margin-right: 0.5rem;"></i>Nom
                </label>
                <input 
                    type="text" 
                    id="nom" 
                    name="nom" 
                    placeholder="Entrez votre nom complet"
                    required
                >
            </div>

            <!-- Préfixe -->
            <div class="form-group">
                <label for="prefixe">
                    <i class="fas fa-mobile-alt" style="color: var(--primary); margin-right: 0.5rem;"></i>Opérateur Mobile
                </label>
                <select id="prefixe" name="id_prefixe" required>
                    <option value="">-- Sélectionnez un opérateur --</option>
                    <?php foreach($prefixes as $prefixe): ?>
                        <option value="<?= esc($prefixe['id']) ?>">
                            <?= esc($prefixe['prefixe']) ?> - <?= esc($prefixe['operateur'] ?? 'Opérateur') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Numéro de téléphone -->
            <div class="form-group">
                <label for="numero">
                    <i class="fas fa-phone" style="color: var(--primary); margin-right: 0.5rem;"></i>Numéro de Téléphone
                </label>
                <input 
                    type="tel" 
                    id="numero" 
                    name="numero" 
                    placeholder="Ex: 1111111"
                    required
                >
            </div>

            <!-- Bouton de soumission -->
            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Se Connecter
                </button>
            </div>
        </form>

        <!-- Information utile -->
        <div class="login-info">
            <i class="fas fa-info-circle"></i>
            <strong>Premier accès ?</strong><br>
            Créez automatiquement un compte en saisissant vos informations pour la première fois.
        </div>
    </div>
</div>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>