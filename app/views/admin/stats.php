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
    <h1 class="text-3xl font-bold text-gray-800 mb-6"><?= $title ?></h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Livraisons -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
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
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
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
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
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
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
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
        <h2 class="text-xl font-bold text-gray-800 mb-4">Livraisons par statut</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-gray-600">En attente</p>
                <p class="text-2xl font-bold text-yellow-600"><?= $stats['livraisons_en_attente'] ?></p>
            </div>
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600">En cours</p>
                <p class="text-2xl font-bold text-blue-600"><?= $stats['livraisons_en_cours'] ?></p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Livrées</p>
                <p class="text-2xl font-bold text-green-600"><?= $stats['livraisons_livrees'] ?></p>
            </div>
            <div class="p-4 bg-red-50 rounded-lg">
                <p class="text-sm text-gray-600">Annulées</p>
                <p class="text-2xl font-bold text-red-600"><?= $stats['livraisons_annulees'] ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
