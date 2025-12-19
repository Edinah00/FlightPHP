
<?php
// ============================================
// FILE 3: app/views/zones/edit.php
// ============================================
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= $title ?></h1>
            <p class="text-gray-600">Modifiez les informations de la zone</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium"><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <form method="POST" action="/zones/<?= $zone['id_zone'] ?>" class="space-y-6">
                <input type="hidden" name="_method" value="PUT">
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nom de la zone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom_zone" required
                           value="<?= htmlspecialchars($zone['nom_zone']) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Pourcentage de supplément (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="pourcentage_supplement" step="0.01" min="0" required
                           value="<?= htmlspecialchars($zone['pourcentage_supplement']) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"><?= htmlspecialchars($zone['description'] ?? '') ?></textarea>
                </div>

                <div class="flex justify-between pt-4 border-t border-gray-200">
                    <a href="/zones" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-medium transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t-2 border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Zone dangereuse</h3>
                <form method="POST" action="/zones/<?= $zone['id_zone'] ?>" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ? Cette action est irréversible.')">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer la zone
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>