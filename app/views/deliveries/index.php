<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
    <h2><?= $title ?></h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert success">Livraison créée avec succès!</div>
<?php endif; ?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Référence</th>
                <th>Livreur</th>
                <th>Véhicule</th>
                <th>Destination</th>
                <th>Poids (kg)</th>
                <th>Statut</th>
                <th>Date création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($deliveries as $delivery): ?>
            <tr>
                <td><?= $delivery['id_livraison'] ?></td>
                <td><?= $delivery['reference'] ?></td>
                <td><?= $delivery['livreur'] ?></td>
                <td><?= $delivery['immatriculation'] ?></td>
                <td><?= substr($delivery['adresse_destination'], 0, 50) ?>...</td>
                <td><?= $delivery['poids_kg'] ?></td>
                <td><span class="status <?= $delivery['statut'] ?>"><?= ucfirst(str_replace('_', ' ', $delivery['statut'])) ?></span></td>
                <td><?= date('d/m/Y H:i', strtotime($delivery['date_creation'])) ?></td>
                <td>
                    <a href="/deliveries/<?= $delivery['id_livraison'] ?>" class="btn-small">Voir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
