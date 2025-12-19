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
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6"><?= $title ?></h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="/zones/<?= $zone['id_zone'] ?>">
                <!-- Simulation PUT pour FlightPHP -->
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nom de la zone *
                    </label>
                    <input type="text" name="nom_zone" required
                           value="<?= htmlspecialchars($zone['nom_zone']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Pourcentage de supplément (%) *
                    </label>
                    <input type="number" name="pourcentage_supplement" step="0.01" min="0" required
                           value="<?= htmlspecialchars($zone['pourcentage_supplement']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($zone['description'] ?? '') ?></textarea>
                </div>

                <div class="flex justify-between">
                    <a href="/zones" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                        Mettre à jour
                    </button>
                </div>
            </form>

            <!-- Optionnel : Formulaire de suppression rapide -->
            <form method="POST" action="/zones/<?= $zone['id_zone'] ?>" class="mt-4" onsubmit="return confirm('Supprimer cette zone ?')">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Supprimer la zone
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
