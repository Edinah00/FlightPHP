-- Base de données pour système de livraison
-- Compatible MySQL et PostgreSQL
-- Toutes les tables ont le suffixe _liv
-- CHANGEMENT MAJEUR: Coût véhicule saisi PAR LIVRAISON (pas par jour)
CREATE DATABASE gestion_livraison;
use gestion_livraison;
-- Table des livreurs (chauffeurs)
CREATE TABLE livreurs_liv (
    id_livreur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    salaire_par_livraison DECIMAL(10,2) NOT NULL COMMENT 'Salaire fixe par livraison',
    statut ENUM('actif', 'inactif') DEFAULT 'actif'
);

-- Table des véhicules
CREATE TABLE vehicules_liv (
    id_vehicule INT PRIMARY KEY AUTO_INCREMENT,
    immatriculation VARCHAR(20) UNIQUE NOT NULL,
    marque VARCHAR(50),
    modele VARCHAR(50),
    statut ENUM('disponible', 'en_service', 'maintenance') DEFAULT 'disponible'
);

-- Table des zones de livraison
CREATE TABLE zones_livraison_liv (
    id_zone INT PRIMARY KEY AUTO_INCREMENT,
    nom_zone VARCHAR(100) NOT NULL,
    code_postal VARCHAR(10),
    ville VARCHAR(100) NOT NULL
);

-- Table de l'entrepôt unique
CREATE TABLE entrepot_liv (
    id_entrepot INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    adresse TEXT NOT NULL,
    ville VARCHAR(100) NOT NULL,
    code_postal VARCHAR(10)
);

-- Table des colis
CREATE TABLE colis_liv (
    id_colis INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) UNIQUE NOT NULL,
    poids_kg DECIMAL(8,2) NOT NULL,
    prix_par_kg DECIMAL(10,2) NOT NULL COMMENT 'CA par kg',
    description TEXT
);

-- Table des livraisons (MODIFIÉE)
-- Coût véhicule saisi directement dans la livraison
-- Un livreur + un véhicule PAR LIVRAISON
CREATE TABLE livraisons_liv (
    id_livraison INT PRIMARY KEY AUTO_INCREMENT,
    id_colis INT NOT NULL,
    id_livreur INT NOT NULL COMMENT 'Chauffeur affecté à cette livraison',
    id_vehicule INT NOT NULL COMMENT 'Véhicule utilisé pour cette livraison',
    id_entrepot INT NOT NULL DEFAULT 1 COMMENT 'Entrepôt unique de départ',
    id_zone INT NOT NULL,
    adresse_destination TEXT NOT NULL,
    cout_vehicule DECIMAL(10,2) NOT NULL COMMENT 'Coût du véhicule pour CETTE livraison (saisi)',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_livraison DATETIME,
    statut ENUM('en_attente', 'en_cours', 'livre', 'annule') DEFAULT 'en_attente',
    FOREIGN KEY (id_colis) REFERENCES colis_liv(id_colis),
    FOREIGN KEY (id_livreur) REFERENCES livreurs_liv(id_livreur),
    FOREIGN KEY (id_vehicule) REFERENCES vehicules_liv(id_vehicule),
    FOREIGN KEY (id_entrepot) REFERENCES entrepot_liv(id_entrepot),
    FOREIGN KEY (id_zone) REFERENCES zones_livraison_liv(id_zone)
);

-- Vue pour calculer les coûts et bénéfices par livraison
CREATE VIEW vue_benefices_livraisons_liv AS
SELECT 
    l.id_livraison,
    l.date_creation,
    l.date_livraison,
    l.statut,
    c.reference AS reference_colis,
    c.poids_kg,
    c.prix_par_kg,
    (c.poids_kg * c.prix_par_kg) AS chiffre_affaire,
    liv.nom AS nom_livreur,
    liv.prenom AS prenom_livreur,
    liv.salaire_par_livraison,
    v.immatriculation,
    l.cout_vehicule,
    (liv.salaire_par_livraison + l.cout_vehicule) AS cout_revient_total,
    ((c.poids_kg * c.prix_par_kg) - (liv.salaire_par_livraison + l.cout_vehicule)) AS benefice,
    DATE(l.date_creation) AS date_jour,
    DATE_FORMAT(l.date_creation, '%Y-%m') AS mois,
    YEAR(l.date_creation) AS annee
FROM livraisons_liv l
JOIN colis_liv c ON l.id_colis = c.id_colis
JOIN livreurs_liv liv ON l.id_livreur = liv.id_livreur
JOIN vehicules_liv v ON l.id_vehicule = v.id_vehicule
WHERE l.statut != 'annule';

-- ========================================
-- Données de test
-- ========================================

-- Insertion des livreurs (chauffeurs) avec salaire PAR LIVRAISON
INSERT INTO livreurs_liv (nom, prenom, telephone, salaire_par_livraison) VALUES
('Rakoto', 'Jean', '0341234567', 15000.00),
('Rabe', 'Marie', '0341234568', 18000.00),
('Andria', 'Pierre', '0341234569', 16000.00),
('Razafy', 'Sophie', '0341234570', 17000.00);

-- Insertion des véhicules (SANS cout_journalier)
INSERT INTO vehicules_liv (immatriculation, marque, modele) VALUES
('1234 TAH', 'Toyota', 'Hiace'),
('5678 TAH', 'Mitsubishi', 'L300'),
('9012 TAH', 'Isuzu', 'D-Max'),
('3456 TAH', 'Ford', 'Ranger');

-- Insertion des zones
INSERT INTO zones_livraison_liv (nom_zone, code_postal, ville) VALUES
('Centre-ville', '101', 'Antananarivo'),
('Ankorondrano', '101', 'Antananarivo'),
('Ivandry', '101', 'Antananarivo'),
('Tsimbazaza', '101', 'Antananarivo'),
('Behoririka', '101', 'Antananarivo');

-- Insertion de l'entrepôt unique
INSERT INTO entrepot_liv (nom, adresse, ville, code_postal) VALUES
('Entrepôt Principal', 'Rue de la Réunion, Analakely', 'Antananarivo', '101');

-- Insertion des colis
INSERT INTO colis_liv (reference, poids_kg, prix_par_kg, description) VALUES
('COL001', 5.5, 3000.00, 'Électronique'),
('COL002', 12.0, 2500.00, 'Documents'),
('COL003', 8.3, 3500.00, 'Vêtements'),
('COL004', 15.0, 2000.00, 'Alimentation'),
('COL005', 3.2, 4000.00, 'Bijoux'),
('COL006', 20.0, 1800.00, 'Matériaux'),
('COL007', 6.5, 3200.00, 'Livres'),
('COL008', 10.0, 2800.00, 'Jouets');

-- Insertion des livraisons (avec cout_vehicule SAISI par livraison)
INSERT INTO livraisons_liv (id_colis, id_livreur, id_vehicule, id_entrepot, id_zone, adresse_destination, cout_vehicule, date_creation, date_livraison, statut) VALUES
(1, 1, 1, 1, 1, 'Avenue de l''Indépendance, Antaninarenina', 8000.00, '2024-12-10 08:30:00', '2024-12-10 14:20:00', 'livre'),
(2, 2, 2, 1, 2, 'Lot IVA 25 Ankorondrano', 6000.00, '2024-12-10 09:15:00', '2024-12-10 15:45:00', 'livre'),
(3, 3, 3, 1, 3, 'Villa 12 Ivandry', 10000.00, '2024-12-10 10:00:00', '2024-12-10 16:30:00', 'livre'),
(4, 1, 1, 1, 4, 'Rue Rainandriamampandry Tsimbazaza', 9000.00, '2024-12-11 08:00:00', '2024-12-11 13:00:00', 'livre'),
(5, 2, 2, 1, 1, 'Immeuble TANA 2000', 7000.00, '2024-12-11 09:30:00', '2024-12-11 14:15:00', 'livre'),
(6, 4, 4, 1, 5, 'Marché Behoririka', 12000.00, '2024-12-11 10:45:00', NULL, 'en_cours'),
(7, 1, 1, 1, 2, 'Résidence BELLEVUE', 8500.00, '2024-12-12 08:15:00', NULL, 'en_attente'),
(8, 3, 3, 1, 3, 'Lot VB 34 Ivandry', 11000.00, '2024-12-12 09:00:00', NULL, 'en_attente');

-- ========================================
-- Requêtes utiles
-- ========================================

-- 1. Bénéfices par jour
-- SELECT 
--     date_jour,
--     COUNT(*) AS nombre_livraisons,
--     SUM(chiffre_affaire) AS chiffre_affaire_total,
--     SUM(cout_revient_total) AS cout_total,
--     SUM(benefice) AS benefice_total
-- FROM vue_benefices_livraisons_liv
-- GROUP BY date_jour
-- ORDER BY date_jour DESC;

-- 2. Bénéfices par mois
-- SELECT 
--     mois,
--     COUNT(*) AS nombre_livraisons,
--     SUM(chiffre_affaire) AS chiffre_affaire_total,
--     SUM(cout_revient_total) AS cout_total,
--     SUM(benefice) AS benefice_total
-- FROM vue_benefices_livraisons_liv
-- GROUP BY mois
-- ORDER BY mois DESC;

-- 3. Bénéfices par année
-- SELECT 
--     annee,
--     COUNT(*) AS nombre_livraisons,
--     SUM(chiffre_affaire) AS chiffre_affaire_total,
--     SUM(cout_revient_total) AS cout_total,
--     SUM(benefice) AS benefice_total
-- FROM vue_benefices_livraisons_liv
-- GROUP BY annee
-- ORDER BY annee DESC;

-- 4. Bénéfice total par véhicule
-- SELECT 
--     v.immatriculation,
--     v.marque,
--     v.modele,
--     COUNT(*) AS nombre_livraisons,
--     SUM(vb.chiffre_affaire) AS ca_total,
--     SUM(vb.cout_revient_total) AS cout_total,
--     SUM(vb.benefice) AS benefice_total
-- FROM vue_benefices_livraisons_liv vb
-- JOIN vehicules_liv v ON vb.immatriculation = v.immatriculation
-- GROUP BY v.id_vehicule
-- ORDER BY benefice_total DESC;

-- 5. Bénéfice par chauffeur
-- SELECT 
--     CONCAT(nom_livreur, ' ', prenom_livreur) AS chauffeur,
--     COUNT(*) AS nombre_livraisons,
--     SUM(chiffre_affaire) AS ca_total,
--     SUM(salaire_par_livraison) AS salaires_percus,
--     SUM(benefice) AS benefice_apporte
-- FROM vue_benefices_livraisons_liv
-- GROUP BY nom_livreur, prenom_livreur
-- ORDER BY benefice_apporte DESC;