-- Base de données pour système de livraison

-- Table des livreurs
CREATE TABLE livreurs_liv (
    id_livreur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    salaire_journalier DECIMAL(10,2) NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif'
);

-- Table des véhicules
CREATE TABLE vehicules_liv (
    id_vehicule INT PRIMARY KEY AUTO_INCREMENT,
    immatriculation VARCHAR(20) UNIQUE NOT NULL,
    marque VARCHAR(50),
    modele VARCHAR(50),
    cout_journalier DECIMAL(10,2) NOT NULL,
    statut ENUM('disponible', 'en_service', 'maintenance') DEFAULT 'disponible'
);

-- Table des zones de livraison
CREATE TABLE zones_livraison_liv (
    id_zone INT PRIMARY KEY AUTO_INCREMENT,
    nom_zone VARCHAR(100) NOT NULL,
    code_postal VARCHAR(10),
    ville VARCHAR(100) NOT NULL
);

-- Table des entrepôts
CREATE TABLE entrepots_liv (
    id_entrepot INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    adresse TEXT NOT NULL,
    ville VARCHAR(100) NOT NULL,
    code_postal VARCHAR(10)
);

-- Table d'affectation livreur-véhicule par jour
CREATE TABLE affectations_liv (
    id_affectation INT PRIMARY KEY AUTO_INCREMENT,
    id_livreur INT NOT NULL,
    id_vehicule INT NOT NULL,
    date_affectation DATE NOT NULL,
    FOREIGN KEY (id_livreur) REFERENCES livreurs_liv(id_livreur),
    FOREIGN KEY (id_vehicule) REFERENCES vehicules_liv(id_vehicule),
    UNIQUE(id_livreur, date_affectation),
    UNIQUE(id_vehicule, date_affectation)
);

-- Table des colis
CREATE TABLE colis_liv (
    id_colis INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) UNIQUE NOT NULL,
    poids_kg DECIMAL(8,2) NOT NULL,
    prix_par_kg DECIMAL(10,2) NOT NULL,
    description TEXT
    );

-- Table des livraisons
CREATE TABLE livraisons_liv (
    id_livraison INT PRIMARY KEY AUTO_INCREMENT,
    id_colis INT NOT NULL,
    id_affectation INT NOT NULL,
    id_entrepot INT NOT NULL,
    id_zone INT NOT NULL,
    adresse_destination TEXT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_livraison DATETIME,
    statut ENUM('en_attente', 'en_cours', 'livre', 'annule') DEFAULT 'en_attente',
    FOREIGN KEY (id_colis) REFERENCES colis_liv(id_colis),
    FOREIGN KEY (id_affectation) REFERENCES affectations_liv(id_affectation),
    FOREIGN KEY (id_entrepot) REFERENCES entrepots_liv(id_entrepot),
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
    (c.poids_kg * c.prix_par_kg) AS revenu_colis,
    liv.nom AS nom_livreur,
    liv.prenom AS prenom_livreur,
    liv.salaire_journalier,
    v.immatriculation,
    v.cout_journalier AS cout_vehicule,
    (liv.salaire_journalier + v.cout_journalier) AS cout_revient_total,
    ((c.poids_kg * c.prix_par_kg) - (liv.salaire_journalier + v.cout_journalier)) AS benefice,
    DATE(l.date_creation) AS date_jour,
    DATE_FORMAT(l.date_creation, '%Y-%m') AS mois,
    YEAR(l.date_creation) AS annee
FROM livraisons_liv l
JOIN colis_liv c ON l.id_colis = c.id_colis
JOIN affectations_liv a ON l.id_affectation = a.id_affectation
JOIN livreurs_liv liv ON a.id_livreur = liv.id_livreur
JOIN vehicules_liv v ON a.id_vehicule = v.id_vehicule
WHERE l.statut != 'annule';

-- Données de test

-- Insertion des livreurs
INSERT INTO livreurs_liv (nom, prenom, telephone, salaire_journalier) VALUES
('Rakoto', 'Jean', '0341234567', 25000.00),
('Rabe', 'Marie', '0341234568', 28000.00),
('Andria', 'Pierre', '0341234569', 26000.00),
('Razafy', 'Sophie', '0341234570', 27000.00);

-- Insertion des véhicules
INSERT INTO vehicules_liv (immatriculation, marque, modele, cout_journalier) VALUES
('1234 TAH', 'Toyota', 'Hiace', 15000.00),
('5678 TAH', 'Mitsubishi', 'L300', 12000.00),
('9012 TAH', 'Isuzu', 'D-Max', 18000.00),
('3456 TAH', 'Ford', 'Ranger', 16000.00);

-- Insertion des zones
INSERT INTO zones_livraison_liv (nom_zone, code_postal, ville) VALUES
('Centre-ville', '101', 'Antananarivo'),
('Ankorondrano', '101', 'Antananarivo'),
('Ivandry', '101', 'Antananarivo'),
('Tsimbazaza', '101', 'Antananarivo'),
('Behoririka', '101', 'Antananarivo');

-- Insertion des entrepôts
INSERT INTO entrepots_liv (nom, adresse, ville, code_postal) VALUES
('Entrepôt Principal', 'Rue de la Réunion, Analakely', 'Antananarivo', '101'),
('Entrepôt Nord', 'Avenue de l''Indépendance', 'Antananarivo', '101');

-- Insertion des affectations (exemple pour décembre 2024)
INSERT INTO affectations_liv (id_livreur, id_vehicule, date_affectation) VALUES
(1, 1, '2024-12-10'),
(2, 2, '2024-12-10'),
(3, 3, '2024-12-10'),
(1, 1, '2024-12-11'),
(2, 2, '2024-12-11'),
(4, 4, '2024-12-11'),
(1, 1, '2024-12-12'),
(3, 3, '2024-12-12');

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

-- Insertion des livraisons
INSERT INTO livraisons_liv (id_colis, id_affectation, id_entrepot, id_zone, adresse_destination, date_creation, date_livraison, statut) VALUES
(1, 1, 1, 1, 'Avenue de l''Indépendance, Antaninarenina', '2024-12-10 08:30:00', '2024-12-10 14:20:00', 'livre'),
(2, 2, 1, 2, 'Lot IVA 25 Ankorondrano', '2024-12-10 09:15:00', '2024-12-10 15:45:00', 'livre'),
(3, 3, 1, 3, 'Villa 12 Ivandry', '2024-12-10 10:00:00', '2024-12-10 16:30:00', 'livre'),
(4, 4, 1, 4, 'Rue Rainandriamampandry Tsimbazaza', '2024-12-11 08:00:00', '2024-12-11 13:00:00', 'livre'),
(5, 5, 2, 1, 'Immeuble TANA 2000', '2024-12-11 09:30:00', '2024-12-11 14:15:00', 'livre'),
(6, 6, 1, 5, 'Marché Behoririka', '2024-12-11 10:45:00', NULL, 'en_cours'),
(7, 7, 1, 2, 'Résidence BELLEVUE', '2024-12-12 08:15:00', NULL, 'en_attente'),
(8, 8, 2, 3, 'Lot VB 34 Ivandry', '2024-12-12 09:00:00', NULL, 'en_attente');

-- Requêtes utiles

-- 1. Bénéfices par jour
-- SELECT 
--     date_jour,
--     COUNT(*) AS nombre_livraisons,
--     SUM(revenu_colis) AS revenu_total,
--     SUM(cout_revient_total) AS cout_total,
--     SUM(benefice) AS benefice_total
-- FROM vue_benefices_livraisons_liv
-- GROUP BY date_jour
-- ORDER BY date_jour DESC;

-- 2. Bénéfices par mois
-- SELECT 
--     mois,
--     COUNT(*) AS nombre_livraisons,
--     SUM(revenu_colis) AS revenu_total,
--     SUM(cout_revient_total) AS cout_total,
--     SUM(benefice) AS benefice_total
-- FROM vue_benefices_livraisons_liv
-- GROUP BY mois
-- ORDER BY mois DESC;

-- 3. Bénéfices par année
-- SELECT 
--     annee,
--     COUNT(*) AS nombre_livraisons,
--     SUM(revenu_colis) AS revenu_total,
--     SUM(cout_revient_total) AS cout_total,
--     SUM(benefice) AS benefice_total
-- FROM vue_benefices_livraisons_liv
-- GROUP BY annee
-- ORDER BY annee DESC;

-- 4. Détail d'une livraison avec tous les coûts
-- SELECT * FROM vue_benefices_livraisons_liv WHERE id_livraison = 1;