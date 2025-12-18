<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>benefits</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
<!-- FILE: app/views/deliveries/create.php -->
<h2><?= $title ?></h2>

<?php if (isset($_GET['error'])): ?>
    <div class="alert error">Erreur: <?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="/deliveries/store">
        
        <h3>Informations du colis</h3>
        
        <div class="form-group">
            <label for="reference">Référence colis *</label>
            <input type="text" id="reference" name="reference" required 
                   placeholder="Ex: COL009" pattern="[A-Z]{3}[0-9]{3,}">
        </div>

        <div class="form-group">
            <label for="poids_kg">Poids (kg) *</label>
            <input type="number" id="poids_kg" name="poids_kg" required 
                   step="0.01" min="0.01" placeholder="Ex: 5.5">
        </div>

        <div class="form-group">
            <label for="prix_par_kg">Prix par kg (Ar) - Chiffre d'affaire *</label>
            <input type="number" id="prix_par_kg" name="prix_par_kg" required 
                   step="0.01" min="0" placeholder="Ex: 3000.00">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="Description du colis..."></textarea>
        </div>

        <hr style="margin: 2rem 0;">
        
        <h3>Affectation de la livraison</h3>

        <div class="form-group">
            <label for="id_livreur">Chauffeur (Livreur) *</label>
            <select id="id_livreur" name="id_livreur" required>
                <option value="">-- Sélectionner un chauffeur --</option>
                <?php foreach ($livreurs as $livreur): ?>
                    <option value="<?= $livreur['id_livreur'] ?>" 
                            data-salaire="<?= $livreur['salaire_par_livraison'] ?>">
                        <?= htmlspecialchars($livreur['label']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="help-text">Le salaire du chauffeur est fixé par livraison</small>
        </div>

        <div class="form-group">
            <label for="id_vehicule">Véhicule *</label>
            <select id="id_vehicule" name="id_vehicule" required>
                <option value="">-- Sélectionner un véhicule --</option>
                <?php foreach ($vehicules as $vehicule): ?>
                    <option value="<?= $vehicule['id_vehicule'] ?>">
                        <?= htmlspecialchars($vehicule['label']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="cout_vehicule">Coût du véhicule pour cette livraison (Ar) *</label>
            <input type="number" id="cout_vehicule" name="cout_vehicule" required 
                   step="0.01" min="0" placeholder="Ex: 8000.00">
            <small class="help-text">À saisir : coût d'utilisation du véhicule pour CETTE livraison spécifique</small>
        </div>

        <hr style="margin: 2rem 0;">
        
        <h3>Destination</h3>

        <div class="form-group">
            <label for="id_zone">Zone de livraison *</label>
            <select id="id_zone" name="id_zone" required>
                <option value="">-- Sélectionner une zone --</option>
                <?php foreach ($zones as $zone): ?>
                    <option value="<?= $zone['id_zone'] ?>">
                        <?= htmlspecialchars($zone['nom_zone']) ?> - <?= htmlspecialchars($zone['ville']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="adresse_destination">Adresse de destination *</label>
            <textarea id="adresse_destination" name="adresse_destination" required rows="3" 
                      placeholder="Adresse complète de livraison..."></textarea>
            <small class="help-text">Point de départ : Entrepôt Principal (automatique)</small>
        </div>

        <hr style="margin: 2rem 0;">
        
        <div class="recap-box" id="recap" style="display: none;">
            <h4>Récapitulatif des coûts</h4>
            <div class="recap-grid">
                <div class="recap-item">
                    <span>Chiffre d'affaire (CA) :</span>
                    <strong id="recap-ca">0.00 Ar</strong>
                </div>
                <div class="recap-item">
                    <span>Salaire chauffeur :</span>
                    <strong id="recap-salaire">0.00 Ar</strong>
                </div>
                <div class="recap-item">
                    <span>Coût véhicule :</span>
                    <strong id="recap-vehicule">0.00 Ar</strong>
                </div>
                <div class="recap-item total">
                    <span>Coût de revient total :</span>
                    <strong id="recap-cout">0.00 Ar</strong>
                </div>
                <div class="recap-item benefice">
                    <span>Bénéfice estimé :</span>
                    <strong id="recap-benefice">0.00 Ar</strong>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer la livraison</button>
            <a href="/deliveries" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
// Calculer automatiquement le récapitulatif
function updateRecap() {
    const poids = parseFloat(document.getElementById('poids_kg').value) || 0;
    const prixKg = parseFloat(document.getElementById('prix_par_kg').value) || 0;
    const coutVehicule = parseFloat(document.getElementById('cout_vehicule').value) || 0;
    
    const selectLivreur = document.getElementById('id_livreur');
    const selectedOption = selectLivreur.options[selectLivreur.selectedIndex];
    const salaire = parseFloat(selectedOption.getAttribute('data-salaire')) || 0;
    
    const ca = poids * prixKg;
    const coutTotal = salaire + coutVehicule;
    const benefice = ca - coutTotal;
    
    if (ca > 0 || salaire > 0 || coutVehicule > 0) {
        document.getElementById('recap').style.display = 'block';
        document.getElementById('recap-ca').textContent = ca.toFixed(2) + ' Ar';
        document.getElementById('recap-salaire').textContent = salaire.toFixed(2) + ' Ar';
        document.getElementById('recap-vehicule').textContent = coutVehicule.toFixed(2) + ' Ar';
        document.getElementById('recap-cout').textContent = coutTotal.toFixed(2) + ' Ar';
        document.getElementById('recap-benefice').textContent = benefice.toFixed(2) + ' Ar';
        
        const beneficeEl = document.querySelector('.recap-item.benefice');
        beneficeEl.className = 'recap-item benefice';
        if (benefice >= 0) {
            beneficeEl.classList.add('positive');
        } else {
            beneficeEl.classList.add('negative');
        }
    }
}

document.getElementById('poids_kg').addEventListener('input', updateRecap);
document.getElementById('prix_par_kg').addEventListener('input', updateRecap);
document.getElementById('cout_vehicule').addEventListener('input', updateRecap);
document.getElementById('id_livreur').addEventListener('change', updateRecap);

</script>

<style>
.form-container {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    max-width: 900px;
    margin: 0 auto;
}

.form-container h3 {
    color: #667eea;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
}

input[type="text"],
input[type="number"],
textarea,
select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: #667eea;
}

textarea {
    resize: vertical;
}

.help-text {
    display: block;
    margin-top: 0.3rem;
    font-size: 0.85rem;
    color: #6c757d;
    font-style: italic;
}

.recap-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-radius: 10px;
    border: 2px solid #dee2e6;
    margin-bottom: 2rem;
}

.recap-box h4 {
    color: #667eea;
    margin-bottom: 1rem;
}

.recap-grid {
    display: grid;
    gap: 1rem;
}

.recap-item {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem;
    background: white;
    border-radius: 6px;
}

.recap-item.total {
    background: #667eea;
    color: white;
    font-weight: 600;
}

.recap-item.total strong {
    color: white;
}

.recap-item.benefice {
    font-size: 1.1rem;
    font-weight: 700;
}

.recap-item.benefice.positive {
    background: #d4edda;
    color: #155724;
}

.recap-item.benefice.negative {
    background: #f8d7da;
    color: #721c24;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn {
    padding: 0.8rem 2rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>