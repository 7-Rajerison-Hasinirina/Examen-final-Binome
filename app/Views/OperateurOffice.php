<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace Opérateur') ?> | Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body class="operateur-office-page">
<div class="office-shell">
    <header class="office-header">
        <div class="office-brand">Mobile Money</div>
        <div class="office-actions">
            <span class="user-info operator">⚙️ Opérateur - <strong><?= esc($nom) ?></strong></span>
            <a href="<?= base_url('operateur-office/logout') ?>" class="logout-btn">Se déconnecter</a>
        </div>
    </header>

    <main class="office-content">
        <div class="content-card">
            <h1><?= esc($title) ?></h1>

            <section class="user-section">
                <h2>Vos Informations</h2>
                <div class="info-box">
                    <div class="info-item">
                        <label>Nom:</label>
                        <span><?= esc($nom) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Numéro:</label>
                        <span><?= esc($numero) ?></span>
                    </div>
                </div>
            </section>

            <section class="bareme-section">
                <h2>Barème de Frais</h2>
                <p class="placeholder">Le barème de frais apparaîtra ici...</p>
            </section>

            <section class="history-section">
                <h2>Historique des Opérations</h2>
                <p class="placeholder">L'historique des opérations apparaîtra ici...</p>
            </section>
        </div>
    </main>

    <footer class="office-footer">
        Mobile Money - Plateforme de Transfert d'Argent
    </footer>
</div>
</body>
</html>
