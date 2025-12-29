<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Avertissement en haut -->
        <div class="bg-red-600 text-white rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex items-center">
                <span class="text-6xl mr-4">⚠️</span>
                <div>
                    <h1 class="text-2xl font-bold">ZONE DANGEREUSE</h1>
                    <p class="text-red-100 mt-1">Actions irréversibles - Procéder avec prudence</p>
                </div>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                ✓ Toutes les livraisons et colis ont été supprimés avec succès !
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                ✗ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Section Effacement des livraisons -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2 border-red-500">
            <div class="bg-red-50 px-6 py-4 border-b-2 border-red-500">
                <h2 class="text-xl font-bold text-red-800">
                    🗑️ Effacer toutes les livraisons
                </h2>
            </div>

            <div class="p-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-2xl">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>ATTENTION :</strong> Cette action va supprimer définitivement :
                            </p>
                            <ul class="list-disc list-inside text-sm text-yellow-700 mt-2">
                                <li>Toutes les livraisons (en attente, en cours, livrées, annulées)</li>
                                <li>Tous les colis associés aux livraisons</li>
                                <li>Toutes les données financières liées</li>
                            </ul>
                            <p class="text-sm text-yellow-700 mt-2 font-bold">
                                ⚠️ Cette action est IRRÉVERSIBLE et ne peut PAS être annulée !
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="/admin/delete-all-deliveries" 
                      onsubmit="return confirmDeletion()" class="space-y-4">
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Pour confirmer, saisissez le code : <span class="text-red-600 text-lg">9999</span>
                        </label>
                        <input type="text" 
                               name="confirmation_code" 
                               id="confirmationCode"
                               maxlength="4"
                               required
                               class="w-full px-4 py-3 text-2xl text-center border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Saisir 9999">
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <input type="checkbox" 
                               id="understand" 
                               required
                               class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="understand" class="ml-3 text-sm text-gray-700">
                            Je comprends que cette action est <strong>irréversible</strong> et 
                            <strong>supprimera définitivement toutes les données</strong>
                        </label>
                    </div>

                    <div class="flex justify-between pt-4">
                        <a href="/deliveries" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-6 py-3 rounded-lg transition">
                            ← Annuler et retourner
                        </a>
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3 rounded-lg transition shadow-lg">
                            🗑️ EFFACER TOUT
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-bold text-blue-800 mb-2">ℹ️ Informations</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Les livreurs, véhicules et zones de livraison ne seront PAS supprimés</li>
                <li>• L'entrepôt ne sera PAS supprimé</li>
                <li>• Seules les livraisons et leurs colis seront effacés</li>
                <li>• Vous pourrez créer de nouvelles livraisons après cette opération</li>
            </ul>
        </div>
    </div>
</div>

<script>
function confirmDeletion() {
    const code = document.getElementById('confirmationCode').value;
    
    if (code !== '9999') {
        alert('⚠️ Code de confirmation incorrect ! Vous devez saisir exactement : 9999');
        return false;
    }
    
    return confirm(
        '🚨 DERNIÈRE CONFIRMATION 🚨\n\n' +
        'Vous êtes sur le point d\'EFFACER DÉFINITIVEMENT :\n' +
        '• Toutes les livraisons\n' +
        '• Tous les colis\n' +
        '• Toutes les données associées\n\n' +
        'Cette action est IRRÉVERSIBLE !\n\n' +
        'Voulez-vous vraiment continuer ?'
    );
}
</script>