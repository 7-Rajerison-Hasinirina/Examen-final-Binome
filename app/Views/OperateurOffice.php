<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace Opérateur') ?> | Mobile Money</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #1a3a52;
            --primary: #2c5aa0;
            --accent: #00d4ff;
            --text: #333333;
            --muted: #666666;
            --border: #e0e0e0;
            --bg: #f8f9fa;
            --success: #00d084;
            --danger: #ff6b6b;
            --warning: #ffa500;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f6f9fd;
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .page-shell {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 290px;
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: #ffffff;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .sidebar .brand {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .sidebar .user-pill {
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 0.75rem;
            padding: 0.8rem;
            font-size: 0.95rem;
        }

        .nav-btn {
            border: none;
            background: rgba(255,255,255,0.12);
            color: #ffffff;
            text-align: left;
            padding: 0.8rem 0.9rem;
            border-radius: 0.7rem;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            transition: all 0.2s ease;
        }

        .nav-btn:hover,
        .nav-btn.active {
            background: rgba(255,255,255,0.24);
            transform: translateX(2px);
        }

        .logout-btn {
            margin-top: auto;
            color: #ffffff;
            text-decoration: none;
            padding: 0.8rem 0.9rem;
            text-align: center;
            border-radius: 0.7rem;
            background: rgba(255,255,255,0.16);
            font-weight: 600;
        }

        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ffffff;
        }

        .topbar {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9fbfd;
        }

        .topbar h1 {
            margin: 0;
            font-size: 1.15rem;
            color: var(--primary-dark);
        }

        .content-area {
            flex: 1;
            padding: 1.15rem;
            overflow: auto;
            background: linear-gradient(135deg, #ffffff 0%, #f7fbff 100%);
        }

        .alert {
            padding: 0.9rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #e8fdf2;
            color: #166534;
            border-color: #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .panel {
            display: none;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.2rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .panel.active {
            display: block;
        }

        .panel h2 {
            color: var(--primary);
            margin-top: 0;
            font-size: 1.1rem;
        }

        .panel p { color: var(--muted); }

        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-bottom: 0.75rem;
        }

        .field label { font-weight: 600; color: var(--primary-dark); }
        .field input, .field select { padding: 0.7rem 0.8rem; border: 1px solid var(--border); border-radius: 0.6rem; font-size: 0.95rem; }

        .btn { border: none; border-radius: 0.6rem; padding: 0.7rem 1rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem; }
        .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: #ffffff; }
        .btn-secondary { background: #eef4ff; color: var(--primary); }
        .btn-warning { background: linear-gradient(135deg, var(--warning) 0%, #ff9500 100%); color: #ffffff; }

        table { width: 100%; border-collapse: collapse; margin-top: 0.8rem; }
        th, td { padding: 0.8rem; border-bottom: 1px solid var(--border); text-align: left; font-size: 0.95rem; }
        th { background: #f0f7ff; color: var(--primary); }
        .actions a { color: var(--primary); font-weight: 600; text-decoration: none; margin-right: 0.8rem; }
        .muted { color: var(--muted); }
        .pill { display: inline-block; padding: 0.25rem 0.55rem; border-radius: 999px; background: #e3f2fd; color: var(--primary); font-weight: 600; font-size: 0.85rem; }

        @media (max-width: 900px) {
            .page-shell { flex-direction: column; }
            .sidebar { width: 100%; flex-direction: row; flex-wrap: wrap; align-items: center; }
            .logout-btn { margin-top: 0; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <aside class="sidebar">
            <div class="brand"><i class="fas fa-coins"></i> Mobile Money</div>
            <div class="user-pill"><i class="fas fa-user-cog"></i> Opérateur - <?= esc($nom) ?></div>
            <button class="nav-btn active" data-target="prefixes"><i class="fas fa-sitemap"></i> Configurations de prefixes</button>
            <button class="nav-btn" data-target="operations"><i class="fas fa-exchange-alt"></i> Creation des types d'operations</button>
            <button class="nav-btn" data-target="gains"><i class="fas fa-chart-line"></i> Situation gain</button>
            <button class="nav-btn" data-target="clients"><i class="fas fa-users"></i> Situation des comptes clients</button>
            <a class="logout-btn" href="<?= base_url('operateur-office/logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <h1><i class="fas fa-user-shield"></i> Espace opérateur</h1>
                <div class="muted">Gestion simple et rapide</div>
            </header>

            <div class="content-area">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <section class="panel active" id="prefixes">
                    <h2><i class="fas fa-sitemap"></i> Configuration des préfixes</h2>
                    <p>Ajoutez ou modifiez les préfixes d’opérateurs.</p>
                    <form method="get" action="<?= base_url('operateur-office') ?>">
                        <input type="hidden" name="action" value="save_prefix">
                        <?php if (!empty($editPrefix)): ?>
                            <input type="hidden" name="id" value="<?= esc($editPrefix['id']) ?>">
                        <?php endif; ?>
                        <div class="grid">
                            <div class="field">
                                <label for="prefixe">Préfixe</label>
                                <input type="text" id="prefixe" name="prefixe" maxlength="3" value="<?= esc($editPrefix['prefixe'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label for="operateur">Nom de l’opérateur</label>
                                <input type="text" id="operateur" name="operateur" value="<?= esc($editPrefix['operateur'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> <?= !empty($editPrefix) ? 'Modifier' : 'Ajouter' ?></button>
                            </div>
                            <?php if (!empty($editPrefix)): ?>
                                <div class="field">
                                    <label>&nbsp;</label>
                                    <a class="btn btn-secondary" href="<?= base_url('operateur-office') ?>"><i class="fas fa-times"></i> Annuler</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>

                    <table>
                        <thead><tr><th>Préfixe</th><th>Opérateur</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php if (!empty($prefixes)): ?>
                                <?php foreach ($prefixes as $prefix): ?>
                                    <tr>
                                        <td><span class="pill"><?= esc($prefix['prefixe']) ?></span></td>
                                        <td><?= esc($prefix['operateur']) ?></td>
                                        <td class="actions"><a href="<?= base_url('operateur-office?edit_prefix_id=' . $prefix['id']) ?>"><i class="fas fa-edit"></i> Modifier</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="muted">Aucun préfixe enregistré.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>

                <section class="panel" id="operations">
                    <h2><i class="fas fa-exchange-alt"></i> Création des types d’opérations</h2>
                    <p>Ajoutez ou modifiez les types d’opérations et leurs libellés.</p>
                    <form method="get" action="<?= base_url('operateur-office') ?>">
                        <input type="hidden" name="action" value="save_operation">
                        <?php if (!empty($editOperation)): ?>
                            <input type="hidden" name="id" value="<?= esc($editOperation['id']) ?>">
                        <?php endif; ?>
                        <div class="grid">
                            <div class="field">
                                <label for="libelle">Libellé</label>
                                <input type="text" id="libelle" name="libelle" value="<?= esc($editOperation['libelle'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> <?= !empty($editOperation) ? 'Modifier' : 'Ajouter' ?></button>
                            </div>
                            <?php if (!empty($editOperation)): ?>
                                <div class="field">
                                    <label>&nbsp;</label>
                                    <a class="btn btn-secondary" href="<?= base_url('operateur-office') ?>"><i class="fas fa-times"></i> Annuler</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>

                    <table>
                        <thead><tr><th>Libellé</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php if (!empty($operationTypes)): ?>
                                <?php foreach ($operationTypes as $type): ?>
                                    <tr>
                                        <td><?= esc($type['libelle']) ?></td>
                                        <td class="actions"><a href="<?= base_url('operateur-office?edit_operation_id=' . $type['id']) ?>"><i class="fas fa-edit"></i> Modifier</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="muted">Aucun type d’opération enregistré.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>

                <section class="panel" id="gains">
                    <h2><i class="fas fa-percent"></i> Barème de frais</h2>
                    <p>Filtrez par type d’opération et par opérateur, puis ajoutez ou modifiez un barème.</p>
                    <form method="get" action="<?= base_url('operateur-office') ?>">
                        <input type="hidden" name="action" value="filter_bareme">
                        <div class="grid">
                            <div class="field">
                                <label for="type_id">Type d’opération</label>
                                <select id="type_id" name="type_id">
                                    <option value="">Tous</option>
                                    <?php foreach ($operationTypes as $type): ?>
                                        <option value="<?= esc($type['id']) ?>" <?= ($selectedTypeId == $type['id']) ? 'selected' : '' ?>><?= esc($type['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <label for="operateur_id">Opérateur</label>
                                <select id="operateur_id" name="operateur_id">
                                    <option value="">Tous</option>
                                    <?php foreach ($operateurs as $op): ?>
                                        <option value="<?= esc($op['id']) ?>" <?= ($selectedOperateurId == $op['id']) ? 'selected' : '' ?>><?= esc($op['prefixe'] . ' - ' . $op['operateur']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <label>&nbsp;</label>
                                <button class="btn btn-secondary" type="submit"><i class="fas fa-filter"></i> Filtrer</button>
                            </div>
                        </div>
                    </form>

                    <form method="get" action="<?= base_url('operateur-office') ?>">
                        <input type="hidden" name="action" value="save_bareme">
                        <?php if (!empty($editBareme)): ?>
                            <input type="hidden" name="id" value="<?= esc($editBareme['id']) ?>">
                        <?php endif; ?>
                        <div class="grid">
                            <div class="field">
                                <label for="id_type_operation">Type d’opération</label>
                                <select id="id_type_operation" name="id_type_operation" required>
                                    <option value="">Sélectionner</option>
                                    <?php foreach ($operationTypes as $type): ?>
                                        <option value="<?= esc($type['id']) ?>" <?= (!empty($editBareme) && $editBareme['id_type_operation'] == $type['id']) ? 'selected' : '' ?>><?= esc($type['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <label for="intervalle1">Intervalle 1</label>
                                <input type="number" id="intervalle1" name="intervalle1" step="0.01" value="<?= esc($editBareme['intervalle1'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label for="intervalle2">Intervalle 2</label>
                                <input type="number" id="intervalle2" name="intervalle2" step="0.01" value="<?= esc($editBareme['intervalle2'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label for="frais">Frais</label>
                                <input type="number" id="frais" name="frais" step="0.01" value="<?= esc($editBareme['frais'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label for="id_operateur">Opérateur</label>
                                <select id="id_operateur" name="id_operateur" required>
                                    <option value="">Sélectionner</option>
                                    <?php foreach ($operateurs as $op): ?>
                                        <option value="<?= esc($op['id']) ?>" <?= (!empty($editBareme) && $editBareme['id_operateur'] == $op['id']) ? 'selected' : '' ?>><?= esc($op['prefixe'] . ' - ' . $op['operateur']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <label>&nbsp;</label>
                                <button class="btn btn-warning" type="submit"><i class="fas fa-save"></i> <?= !empty($editBareme) ? 'Modifier' : 'Ajouter' ?></button>
                            </div>
                        </div>
                    </form>

                    <table>
                        <thead><tr><th>Type</th><th>Intervalle</th><th>Frais</th><th>Opérateur</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php if (!empty($baremes)): ?>
                                <?php foreach ($baremes as $bareme): ?>
                                    <tr>
                                        <td><?= esc($bareme['type_operation']) ?></td>
                                        <td><?= esc($bareme['intervalle1']) ?> - <?= esc($bareme['intervalle2']) ?></td>
                                        <td><?= esc($bareme['frais']) ?> Ar</td>
                                        <td><?= esc($bareme['prefixe_operateur']) ?></td>
                                        <td class="actions"><a href="<?= base_url('operateur-office?edit_bareme_id=' . $bareme['id']) ?>"><i class="fas fa-edit"></i> Modifier</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="muted">Aucun barème enregistré.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>

                <section class="panel" id="clients">
                    <h2><i class="fas fa-users"></i> Situation des comptes clients</h2>
                    <table>
                        <thead><tr><th>Client</th><th>Référence</th><th>Solde actuel</th></tr></thead>
                        <tbody>
                            <?php if (!empty($clients)): ?>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><?= esc($client['nom']) ?></td>
                                        <td><?= esc($client['reference']) ?></td>
                                        <td><?= esc($client['solde']) ?> Ar</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="muted">Aucun client trouvé.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.querySelectorAll('.nav-btn').forEach(function(btn){
            btn.addEventListener('click', function(){
                document.querySelectorAll('.nav-btn').forEach(function(item){ item.classList.remove('active'); });
                document.querySelectorAll('.panel').forEach(function(panel){ panel.classList.remove('active'); });
                btn.classList.add('active');
                document.getElementById(btn.getAttribute('data-target')).classList.add('active');
            });
        });
    </script>
</body>
</html>
