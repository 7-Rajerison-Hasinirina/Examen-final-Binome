<?php if (! empty($produits) && is_array($produits)) : ?>
<h1>Liste des produits</h1>
<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prix</th>
    </tr>
    <?php foreach ($produits as $p): ?>
    <tr>
        <td><?= esc($p['id']) ?></td>
        <td><?= esc($p['nom']) ?></td>
        <td><?= esc($p['prix']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>Aucun produit trouvé. Exécutez la migration pour créer la table.</p>
<?php endif; ?>
