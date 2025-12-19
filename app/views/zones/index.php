<?php
// ============================================
// FILE 1: app/views/zones/index.php
// ============================================
?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= $title ?></h1>
            <p class="text-gray-600">Gérez les zones de livraison et leurs suppléments</p>
        </div>
        <a href="/zones/create" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg><?php
// ============================================
// FILE 1: app/views/zones/index.php
// ============================================
?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= $title ?></h1>
            <p class="text-gray-600">Gérez les zones de livraison et leurs suppléments</p>
        </div>
        <a href="/zones/create" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle zone
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg mb-6 shadow-sm animate-fade-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">
                    <?php if ($success === 'created'): ?>
                        Zone créée avec succès !
                    <?php elseif ($success === 'updated'): ?>
                        Zone modifiée avec succès !
                    <?php elseif ($success === 'deleted'): ?>
                        Zone supprimée avec succès !
                    <?php endif; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium"><?= htmlspecialchars($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nom de la zone</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Supplément</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Livraisons</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($zones as $zone): ?>
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-900"><?= htmlspecialchars($zone['nom_zone']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold shadow-sm
                                    <?= $zone['pourcentage_supplement'] > 0 
                                        ? 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300' 
                                        : 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300' ?>">
                                    <?= number_format($zone['pourcentage_supplement'], 2) ?>%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                <?= htmlspecialchars($zone['description'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <?= $zone['nombre_livraisons'] ?> livraison(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-3">
                                    <a href="/zones/<?= $zone['id_zone'] ?>/edit" 
                                       class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition-colors">
                                        Modifier
                                    </a>
                                    <?php if ($zone['nombre_livraisons'] == 0): ?>
                                        <form method="POST" action="/zones/<?= $zone['id_zone'] ?>/delete" 
                                              class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium hover:underline transition-colors">
                                                Supprimer
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-gray-400 cursor-not-allowed" title="Impossible de supprimer une zone avec des livraisons">
                                            Supprimer
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (empty($zones)): ?>
        <div class="text-center py-12 bg-white rounded-2xl shadow-lg border border-gray-100 mt-6">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune zone de livraison</h3>
            <p class="text-gray-500 mb-6">Commencez par créer votre première zone de livraison</p>
            <a href="/zones/create" class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                Créer une zone
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

            Nouvelle zone
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg mb-6 shadow-sm animate-fade-in">
            <div class="flex items-center">
               
                <span class="font-medium">
                    <?php if ($success === 'created'): ?>
                        Zone créée avec succès !
                    <?php elseif ($success === 'updated'): ?>
                        Zone modifiée avec succès !
                    <?php elseif ($success === 'deleted'): ?>
                        Zone supprimée avec succès !
                    <?php endif; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-sm">
            <div class="flex items-center">
                
                <span class="font-medium"><?= htmlspecialchars($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nom de la zone</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Supplément</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Livraisons</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($zones as $zone): ?>
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-900"><?= htmlspecialchars($zone['nom_zone']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold shadow-sm
                                    <?= $zone['pourcentage_supplement'] > 0 
                                        ? 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300' 
                                        : 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300' ?>">
                                    <?= number_format($zone['pourcentage_supplement'], 2) ?>%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                <?= htmlspecialchars($zone['description'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <?= $zone['nombre_livraisons'] ?> livraison(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-3">
                                    <a href="/zones/<?= $zone['id_zone'] ?>/edit" 
                                       class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition-colors">
                                        Modifier
                                    </a>
                                    <?php if ($zone['nombre_livraisons'] == 0): ?>
                                        <form method="POST" action="/zones/<?= $zone['id_zone'] ?>/delete" 
                                              class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium hover:underline transition-colors">
                                                Supprimer
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-gray-400 cursor-not-allowed" title="Impossible de supprimer une zone avec des livraisons">
                                            Supprimer
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (empty($zones)): ?>
        <div class="text-center py-12 bg-white rounded-2xl shadow-lg border border-gray-100 mt-6">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune zone de livraison</h3>
            <p class="text-gray-500 mb-6">Commencez par créer votre première zone de livraison</p>
            <a href="/zones/create" class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                Créer une zone
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
