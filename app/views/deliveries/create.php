

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    
</head>
<body>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6"><?= $title ?></h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                ✗ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Formulaire -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <form method="POST" action="/deliveries" id="deliveryForm">
                        <!-- SECTION COLIS -->
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-700 mb-4 pb-2 border-b">📦 Informations du colis</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Référence *</label>
                                    <input type="text" name="reference" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Ex: COL-2025-001">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Poids (kg) *</label>
                                    <input type="number" name="poids_kg" id="poids_kg" step="0.01" min="0.01" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Ex: 25.5">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Prix par kg (Ar) *</label>
                                    <input type="number" name="prix_par_kg" id="prix_par_kg" step="0.01" min="0.01" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Ex: 5000">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                    <input type="text" name="description"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Ex: Colis alimentaire">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION LIVRAISON -->
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-700 mb-4 pb-2 border-b">🚚 Détails de la livraison</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Zone de livraison *
                                    </label>
                                    <select name="id_zone" id="id_zone" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Sélectionner --</option>
                                        <?php foreach ($zones as $zone): ?>
                                            <option value="<?= $zone['id_zone'] ?>" 
                                                    data-supplement="<?= $zone['pourcentage_supplement'] ?>">
                                                <?= htmlspecialchars($zone['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Adresse de destination *</label>
                                    <input type="text" name="adresse_destination" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Ex: Rue Rainibetsimisaraka, Analakely">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Livreur *</label>
                                    <select name="id_livreur" id="id_livreur" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Sélectionner --</option>
                                        <?php foreach ($livreurs as $livreur): ?>
                                            <option value="<?= $livreur['id_livreur'] ?>"
                                                    data-salaire="<?= $livreur['salaire_par_livraison'] ?>">
                                                <?= htmlspecialchars($livreur['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Véhicule *</label>
                                    <select name="id_vehicule" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Sélectionner --</option>
                                        <?php foreach ($vehicules as $vehicule): ?>
                                            <option value="<?= $vehicule['id_vehicule'] ?>">
                                                <?= htmlspecialchars($vehicule['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Coût véhicule (Ar) *</label>
                                    <input type="number" name="cout_vehicule" id="cout_vehicule" step="0.01" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Ex: 30000">
                                </div>
                            </div>
                        </div>

                        <!-- BOUTONS -->
                        <div class="flex justify-between pt-4">
                            <a href="/deliveries" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">← Annuler</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">✓ Créer la livraison</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panneau de calcul en temps réel -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg shadow-lg p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">💰 Aperçu financier</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-blue-200">
                            <span class="text-gray-600">Montant de base</span>
                            <span class="font-bold" id="montant_base">0 Ar</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-blue-200">
                            <span class="text-gray-600">Supplément zone <span id="supplement_percent">(0%)</span></span>
                            <span class="font-bold" id="montant_supplement">0 Ar</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b-2 border-blue-300 bg-blue-100 px-2 rounded">
                            <span class="font-bold text-blue-800">Chiffre d'affaire</span>
                            <span class="font-bold text-blue-800" id="chiffre_affaire">0 Ar</span>
                        </div>
                        
                        <div class="pt-2">
                            <p class="font-semibold text-gray-700 mb-2">Coûts :</p>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">• Salaire livreur</span>
                                <span id="salaire_livreur">0 Ar</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">• Coût véhicule</span>
                                <span id="cout_vehicule_display">0 Ar</span>
                            </div>
                            <div class="flex justify-between py-2 font-semibold border-t border-blue-200 mt-2">
                                <span>Total coûts</span>
                                <span id="cout_total">0 Ar</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between py-3 bg-gradient-to-r from-green-100 to-emerald-100 px-3 rounded-lg border-2 border-green-300 mt-4">
                            <span class="font-bold text-green-800">💎 Bénéfice net</span>
                            <span class="font-bold text-lg text-green-800" id="benefice">0 Ar</span>
                        </div>
                        
                        <div class="text-center pt-2">
                            <span class="text-xs text-gray-600">Marge : </span>
                            <span class="text-sm font-bold" id="marge">0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCalculations() {
    const poids = parseFloat(document.getElementById('poids_kg').value) || 0;
    const prixParKg = parseFloat(document.getElementById('prix_par_kg').value) || 0;
    const zoneSelect = document.getElementById('id_zone');
    const livreurSelect = document.getElementById('id_livreur');
    const coutVehicule = parseFloat(document.getElementById('cout_vehicule').value) || 0;
    const supplement = zoneSelect.selectedIndex > 0 ? parseFloat(zoneSelect.options[zoneSelect.selectedIndex].dataset.supplement) : 0;
    const salaireLivreur = livreurSelect.selectedIndex > 0 ? parseFloat(livreurSelect.options[livreurSelect.selectedIndex].dataset.salaire) : 0;

    const montantBase = poids * prixParKg;
    const montantSupplement = montantBase * (supplement / 100);
    const chiffreAffaire = montantBase + montantSupplement;
    const coutTotal = salaireLivreur + coutVehicule;
    const benefice = chiffreAffaire - coutTotal;
    const marge = chiffreAffaire > 0 ? (benefice / chiffreAffaire * 100) : 0;

    document.getElementById('montant_base').textContent = montantBase.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('supplement_percent').textContent = `(${supplement}%)`;
    document.getElementById('montant_supplement').textContent = montantSupplement.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('chiffre_affaire').textContent = chiffreAffaire.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('salaire_livreur').textContent = salaireLivreur.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('cout_vehicule_display').textContent = coutVehicule.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('cout_total').textContent = coutTotal.toLocaleString('fr-FR') + ' Ar';
    document.getElementById('benefice').textContent = benefice.toLocaleString('fr-FR') + ' Ar';
    const margeElement = document.getElementById('marge');
    margeElement.textContent = marge.toFixed(2) + '%';
    margeElement.style.color = benefice >= 0 ? '#059669' : '#dc2626';
}

['poids_kg', 'prix_par_kg', 'id_zone', 'id_livreur', 'cout_vehicule'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('input', updateCalculations);
        el.addEventListener('change', updateCalculations);
    }
});
</script>

</body>
</html>