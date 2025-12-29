<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= $title ?></h1>
            <p class="text-gray-600">Définissez une nouvelle zone de livraison</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-sm">
                <div class="flex items-center">
                    <span class="font-medium">✗ <?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <form method="POST" action="/zones" class="space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nom de la zone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom_zone" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           placeholder="Ex: Antananarivo Centre">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Pourcentage de supplément (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="pourcentage_supplement" step="0.01" min="0" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           placeholder="Ex: 12.5">
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                         Mettre 0 pour aucun supplément
                    </p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                              placeholder="Description de la zone..."></textarea>
                </div>

                <div class="flex justify-between pt-4 border-t border-gray-200">
                    <a href="/zones" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-medium transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        Créer la zone
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>