<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6"><?= $title ?></h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Livraisons -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <span class="text-3xl text-white">📦</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 uppercase">Total Livraisons</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_livraisons'] ?></p>
                </div>
            </div>
        </div>

        <!-- Livreurs Actifs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <span class="text-3xl text-white">👥</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 uppercase">Livreurs Actifs</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_livreurs'] ?></p>
                </div>
            </div>
        </div>

        <!-- Véhicules -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <span class="text-3xl text-white">🚚</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 uppercase">Véhicules</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_vehicules'] ?></p>
                </div>
            </div>
        </div>

        <!-- Zones -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <span class="text-3xl text-white">🗺️</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 uppercase">Zones</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_zones'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Détail par statut -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📊 Livraisons par statut</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                <p class="text-sm text-gray-600 mb-1">⏳ En attente</p>
                <p class="text-2xl font-bold text-yellow-600"><?= $stats['livraisons_en_attente'] ?></p>
            </div>
            <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                <p class="text-sm text-gray-600 mb-1">🚀 En cours</p>
                <p class="text-2xl font-bold text-blue-600"><?= $stats['livraisons_en_cours'] ?></p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-400">
                <p class="text-sm text-gray-600 mb-1">✅ Livrées</p>
                <p class="text-2xl font-bold text-green-600"><?= $stats['livraisons_livrees'] ?></p>
            </div>
            <div class="p-4 bg-red-50 rounded-lg border-l-4 border-red-400">
                <p class="text-sm text-gray-600 mb-1">❌ Annulées</p>
                <p class="text-2xl font-bold text-red-600"><?= $stats['livraisons_annulees'] ?></p>
            </div>
        </div>
    </div>
</div>