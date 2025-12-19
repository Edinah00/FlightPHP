<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>benefits</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
<!-- FILE: app/views/deliveries/view.php -->
<h2><?= $title ?></h2>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert success">Statut mis à jour avec succès!</div>
<?php endif; ?>

<div class="delivery-detail">
    
    <!-- Statut et actions -->
    <div class="status-card">
        <div class="status-header">
            <span class="status-badge status-<?= $delivery['statut'] ?>">
                <?= ucfirst(str_replace('_', ' ', $delivery['statut'])) ?>
            </span>
            <div class="status-dates">
                <small>Créée le: <?= date('d/m/Y à H:i', strtotime($delivery['date_creation'])) ?></small>
                <?php if ($delivery['date_livraison']): ?>
                    <small>Livrée le: <?= date('d/m/Y à H:i', strtotime($delivery['date_livraison'])) ?></small>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($delivery['statut'] !== 'livre' && $delivery['statut'] !== 'annule'): ?>
        <form method="POST" action="/deliveries/<?= $delivery['id_livraison'] ?>/update-status" class="status-form">
            <label for="status">Changer le statut:</label>
            <select name="status" id="status">
                <option value="en_attente" <?= $delivery['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                <option value="en_cours" <?= $delivery['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                <option value="livre">Livré</option>
                <option value="annule">Annulé</option>
            </select>
            <button type="submit" class="btn btn-small">Mettre à jour</button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Informations du colis -->
    <div class="info-section">
        <h3> Informations du colis</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Référence</label>
                <strong><?= htmlspecialchars($delivery['reference']) ?></strong>
            </div>
            <div class="info-item">
                <label>Poids</label>
                <strong><?= $delivery['poids_kg'] ?> kg</strong>
            </div>
            <div class="info-item">
                <label>Prix par kg</label>
                <strong><?= number_format($delivery['prix_par_kg'], 2) ?> Ar</strong>
            </div>
            <div class="info-item">
                <label>Description</label>
                <strong><?= htmlspecialchars($delivery['description'] ?: 'N/A') ?></strong>
            </div>
        </div>
    </div>

    <!-- Affectation -->
    <div class="info-section">
        <h3> Affectation</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Chauffeur (Livreur)</label>
                <strong><?= htmlspecialchars($delivery['livreur']) ?></strong>
            </div>
            <div class="info-item">
                <label>Véhicule</label>
                <strong><?= htmlspecialchars($delivery['immatriculation']) ?> 
                        (<?= htmlspecialchars($delivery['marque']) ?> <?= htmlspecialchars($delivery['modele']) ?>)</strong>
            </div>
        </div>
    </div>

    <!-- Destination -->
    <div class="info-section">
        <h3> Itinéraire</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Départ (Entrepôt)</label>
                <strong><?= htmlspecialchars($delivery['entrepot']) ?></strong>
            </div>
            <div class="info-item">
                <label>Zone de destination</label>
                <strong><?= htmlspecialchars($delivery['nom_zone']) ?></strong>
            </div>
            <div class="info-item full-width">
                <label>Adresse de destination</label>
                <strong><?= htmlspecialchars($delivery['adresse_destination']) ?></strong>
            </div>
        </div>
    </div>

    <!-- Calculs financiers -->
    <div class="info-section financial">
        <h3> Analyse financière</h3>
        
        <div class="financial-grid">
            <div class="financial-item ca">
                <span class="label">Chiffre d'affaire (CA)</span>
                <span class="value"><?= number_format($delivery['chiffre_affaire'], 2) ?> Ar</span>
                <small><?= $delivery['poids_kg'] ?> kg × <?= number_format($delivery['prix_par_kg'], 2) ?> Ar/kg</small>
            </div>

            <div class="financial-breakdown">
                <h4>Coûts de revient</h4>
                <div class="cost-item">
                    <span>Salaire chauffeur</span>
                    <strong><?= number_format($delivery['salaire_par_livraison'], 2) ?> Ar</strong>
                </div>
                <div class="cost-item">
                    <span>Coût véhicule</span>
                    <strong><?= number_format($delivery['cout_vehicule'], 2) ?> Ar</strong>
                </div>
                <div class="cost-total">
                    <span>Total coût de revient</span>
                    <strong><?= number_format($delivery['cout_revient_total'], 2) ?> Ar</strong>
                </div>
            </div>

            <div class="financial-item benefice <?= $delivery['benefice'] >= 0 ? 'positive' : 'negative' ?>">
                <span class="label">Bénéfice</span>
                <span class="value"><?= number_format($delivery['benefice'], 2) ?> Ar</span>
                <small>
                    Marge: <?= number_format(($delivery['benefice'] / $delivery['chiffre_affaire']) * 100, 2) ?>%
                </small>
            </div>
        </div>
    </div>

    <div class="actions">
        <a href="/deliveries" class="btn btn-secondary">← Retour à la liste</a>
    </div>
</div>

<style>
.delivery-detail {
    max-width: 1000px;
    margin: 0 auto;
}

.status-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    margin-bottom: 2rem;
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.status-badge {
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    font-weight: 700;
    font-size: 1.1rem;
}

.status-badge.status-en_attente {
    background: #fff3cd;
    color: #856404;
}

.status-badge.status-en_cours {
    background: #cce5ff;
    color: #004085;
}

.status-badge.status-livre {
    background: #d4edda;
    color: #155724;
}

.status-badge.status-annule {
    background: #f8d7da;
    color: #721c24;
}

.status-dates {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    text-align: right;
}

.status-form {
    display: flex;
    gap: 1rem;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.info-section {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
}

.info-section h3 {
    color: #667eea;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
}

.info-item strong {
    font-size: 1.1rem;
    color: #333;
}

.financial {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.financial-grid {
    display: grid;
    gap: 1.5rem;
}

.financial-item {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.financial-item .label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
}

.financial-item .value {
    font-size: 1.8rem;
    font-weight: 700;
}

.financial-item small {
    color: #6c757d;
    font-size: 0.85rem;
}

.financial-item.ca {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.financial-item.ca .label,
.financial-item.ca small {
    color: rgba(255,255,255,0.9);
}

.financial-item.benefice {
    font-size: 1.2rem;
}

.financial-item.benefice.positive {
    background: #d4edda;
    color: #155724;
}

.financial-item.benefice.negative {
    background: #f8d7da;
    color: #721c24;
}

.financial-breakdown {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
}

.financial-breakdown h4 {
    color: #667eea;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.cost-item {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem;
    margin-bottom: 0.5rem;
    background: #f8f9fa;
    border-radius: 6px;
}

.cost-total {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    margin-top: 0.5rem;
    background: #667eea;
    color: white;
    border-radius: 6px;
    font-weight: 600;
}

.actions {
    margin-top: 2rem;
    text-align: center;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>