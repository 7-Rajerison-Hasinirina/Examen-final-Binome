<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Connexion') ?> | Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body class="login-page">
<div class="login-shell">

    <section class="login-panel">
        <div class="login-form-wrap">
            <p class="login-kicker">Mobile Money</p>
            <h1>Bienvenue</h1>
            <p class="login-subtitle">Veuillez saisir vos informations.</p>

            <?php $security = config('Security'); ?>

            <form action="<?= base_url('login/valider') ?>" method="post">
                <?= csrf_field() ?>

                <div>
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                </div>

                <div>
                    <label for="prefixe">Préfixe</label>
                    <select id="prefixe" name="id_prefixe" required>
                        <?php foreach($prefixes as $prefixe): ?>
                            <option value="<?= esc($prefixe['id']) ?>">
                                <?= esc($prefixe['prefixe']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="numero">Numéro de téléphone</label>
                    <input type="text" id="numero" name="numero" placeholder="Ex: 1111111" required>
                </div>

                <div class="form-actions form-actions-column">
                    <button type="submit" class="submit-btn btn-full">
                        Valider
                    </button>
                </div>

            </form>
        </div>
    </section>

</div>
</body>
</html>