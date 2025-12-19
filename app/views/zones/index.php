<?php
// ============================================
// FILE: app/views/zones/index.php
// ============================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery_system</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/style.css">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
        <a href="/zones/create" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            + Nouvelle zone
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php if ($success === 'created'): ?>
                Zone créée avec succès !
            <?php elseif ($success === 'updated'): ?>
                Zone modifiée avec succès !
            <?php elseif ($success === 'deleted'): ?>
                Zone supprimée avec succès !
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom de la zone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplément</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Livraisons</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($zones as $zone): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($zone['nom_zone']) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?= $zone['pourcentage_supplement'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' ?>">
                                <?= number_format($zone['pourcentage_supplement'], 2) ?>%
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?= htmlspecialchars($zone['description'] ?? '-') ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?= $zone['nombre_livraisons'] ?> livraison(s)
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="/zones/<?= $zone['id_zone'] ?>/edit" 
                               class="text-blue-600 hover:text-blue-800 mr-3">
                                Modifier
                            </a>
                            <?php if ($zone['nombre_livraisons'] == 0): ?>
                                <form method="POST" action="/zones/<?= $zone['id_zone'] ?>/delete" 
                                      class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        Supprimer
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
