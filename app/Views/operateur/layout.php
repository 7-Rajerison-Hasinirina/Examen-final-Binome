<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace Opérateur') ?> | Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>
<body class="bg-light">
    <?php
    $flashSuccess = session()->getFlashdata('success');
    $flashError = session()->getFlashdata('error');
    ?>
    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Sidebar -->
            <aside class="col-12 col-lg-3 col-xl-2 bg-dark text-white p-0 d-flex flex-column">
                <div class="p-4 border-bottom border-secondary">
                    <h1 class="h5 fw-bold mb-1">Mobile Money</h1>
                    <p class="small text-white-50 mb-0">Console d'administration opérateur</p>
                </div>

                <div class="p-4 border-bottom border-secondary">
                    <div class="text-uppercase small text-white-50 mb-1">Session active</div>
                    <div class="fw-bold"><?= esc($nom ?? 'Opérateur') ?></div>
                    <div class="small text-white-50">Numéro : <?= esc($numero ?? '-') ?></div>
                </div>

                <div class="p-3 flex-grow-1">
                    <div class="text-uppercase small text-white-50 mb-2 px-2">Navigation</div>
                    <div class="nav nav-pills flex-column gap-1" id="operateur-tabs" role="tablist" aria-orientation="vertical">
                        <button class="nav-link text-white text-start active" id="tab-btn-dashboard" data-bs-toggle="pill" data-bs-target="#tab-dashboard" type="button" role="tab" aria-controls="tab-dashboard" aria-selected="true">
                            Tableau de bord
                        </button>
                        <button class="nav-link text-white text-start" id="tab-btn-prefixes" data-bs-toggle="pill" data-bs-target="#tab-prefixes" type="button" role="tab" aria-controls="tab-prefixes" aria-selected="false">
                            Configuration des préfixes
                        </button>
                        <button class="nav-link text-white text-start" id="tab-btn-types" data-bs-toggle="pill" data-bs-target="#tab-types" type="button" role="tab" aria-controls="tab-types" aria-selected="false">
                            Types d'opérations
                        </button>
                        <button class="nav-link text-white text-start" id="tab-btn-gains" data-bs-toggle="pill" data-bs-target="#tab-gains" type="button" role="tab" aria-controls="tab-gains" aria-selected="false">
                            Situation de gain
                        </button>
                        <button class="nav-link text-white text-start" id="tab-btn-comptes" data-bs-toggle="pill" data-bs-target="#tab-comptes" type="button" role="tab" aria-controls="tab-comptes" aria-selected="false">
                            Situation des comptes
                        </button>
                        <button class="nav-link text-white text-start" id="tab-btn-operations" data-bs-toggle="pill" data-bs-target="#tab-operations" type="button" role="tab" aria-controls="tab-operations" aria-selected="false">
                            Opérations récentes
                        </button>
                    </div>
                </div>

                <div class="p-3 border-top border-secondary">
                    <a href="<?= base_url('operateur-office/logout') ?>" class="btn btn-outline-danger w-100">
                        Déconnexion
                    </a>
                </div>
            </aside>

            <!-- Main content -->
            <div class="col-12 col-lg-9 col-xl-10 d-flex flex-column p-0">
                <header class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center flex-wrap gap-2 sticky-top">
                    <div>
                        <div class="fw-bold"><?= esc($title ?? 'Espace Opérateur') ?></div>
                        <div class="small text-muted">Gestion des préfixes, comptes et opérations</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('operateur-office') ?>" class="btn btn-outline-secondary btn-sm">Rafraîchir</a>
                        <a href="<?= base_url('operateur-office/logout') ?>" class="btn btn-danger btn-sm">Se déconnecter</a>
                    </div>
                </header>

                <main class="p-3 p-md-4 flex-grow-1">
                    <?php if ($flashSuccess): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= esc((string) $flashSuccess) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashError): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= esc((string) $flashError) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    <?php endif; ?>

                    <?= $this->renderSection('content') ?>
                </main>

                <footer class="p-3 text-center text-muted small border-top">
                    Mobile Money — Espace opérateur sécurisé
                </footer>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Si l'URL contient une ancre (#prefixes, #types, ...), on active
        // automatiquement l'onglet correspondant au chargement de la page.
        document.addEventListener('DOMContentLoaded', function () {
            var hash = window.location.hash.replace('#', '');
            var map = {
                'prefixes': 'tab-btn-prefixes',
                'types': 'tab-btn-types',
                'gains': 'tab-btn-gains',
                'comptes': 'tab-btn-comptes',
                'operations': 'tab-btn-operations'
            };

            if (hash && map[hash]) {
                var trigger = document.getElementById(map[hash]);
                if (trigger) {
                    new bootstrap.Tab(trigger).show();
                }
            }
        });
    </script>
</body>
</html>
