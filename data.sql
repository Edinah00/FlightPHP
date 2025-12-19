

CREATE DATABASE IF NOT EXISTS livraison_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE livraison_db;


DROP TABLE IF EXISTS zones_livraison_liv;
CREATE TABLE zones_livraison_liv (
    id_zone INT AUTO_INCREMENT PRIMARY KEY,
    nom_zone VARCHAR(100) NOT NULL UNIQUE,
    pourcentage_supplement DECIMAL(5,2) DEFAULT 0 COMMENT 'Pourcentage de supplément appliqué (ex: 12.5 pour 12.5%)',
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nom_zone (nom_zone)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS entrepot_liv;
CREATE TABLE entrepot_liv (
    id_entrepot INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    adresse TEXT NOT NULL,
    telephone VARCHAR(20),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


DROP TABLE IF EXISTS vehicules_liv;
CREATE TABLE vehicules_liv (
    id_vehicule INT AUTO_INCREMENT PRIMARY KEY,
    immatriculation VARCHAR(20) NOT NULL UNIQUE,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT,
    statut ENUM('disponible', 'en_service', 'en_maintenance', 'hors_service') DEFAULT 'disponible',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_statut (statut)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS livreurs_liv;
CREATE TABLE livreurs_liv (
    id_livreur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    salaire_par_livraison DECIMAL(10,2) NOT NULL COMMENT 'Salaire fixe par livraison effectuée',
    statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
    date_embauche TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_statut (statut)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS colis_liv;
CREATE TABLE colis_liv (
    id_colis INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(50) NOT NULL UNIQUE,
    poids_kg DECIMAL(10,2) NOT NULL,
    prix_par_kg DECIMAL(10,2) NOT NULL COMMENT 'Prix facturé par kg',
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_reference (reference)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS livraisons_liv;
CREATE TABLE livraisons_liv (
    id_livraison INT AUTO_INCREMENT PRIMARY KEY,
    id_colis INT NOT NULL,
    id_livreur INT NOT NULL,
    id_vehicule INT NOT NULL,
    id_entrepot INT NOT NULL,
    id_zone INT NOT NULL,
    adresse_destination TEXT NOT NULL,
    cout_vehicule DECIMAL(10,2) NOT NULL COMMENT 'Coût du véhicule pour cette livraison spécifique',
    statut ENUM('en_attente', 'en_cours', 'livre', 'annule') DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_livraison TIMESTAMP NULL,
    FOREIGN KEY (id_colis) REFERENCES colis_liv(id_colis) ON DELETE RESTRICT,
    FOREIGN KEY (id_livreur) REFERENCES livreurs_liv(id_livreur) ON DELETE RESTRICT,
    FOREIGN KEY (id_vehicule) REFERENCES vehicules_liv(id_vehicule) ON DELETE RESTRICT,
    FOREIGN KEY (id_entrepot) REFERENCES entrepot_liv(id_entrepot) ON DELETE RESTRICT,
    FOREIGN KEY (id_zone) REFERENCES zones_livraison_liv(id_zone) ON DELETE RESTRICT,
    INDEX idx_statut (statut),
    INDEX idx_dates (date_creation, date_livraison)
) ENGINE=InnoDB;


DROP VIEW IF EXISTS vue_benefices_livraisons_liv;
CREATE VIEW vue_benefices_livraisons_liv AS
SELECT 
    l.id_livraison,
    l.date_creation,
    l.date_livraison,
    l.statut,
    c.reference,
    c.poids_kg,
    c.prix_par_kg,
    
    -- Calcul financier détaillé
    (c.poids_kg * c.prix_par_kg) AS montant_base,
    z.pourcentage_supplement,
    ((c.poids_kg * c.prix_par_kg) * z.pourcentage_supplement / 100) AS montant_supplement,
    ((c.poids_kg * c.prix_par_kg) * (1 + z.pourcentage_supplement / 100)) AS chiffre_affaire,
    
    -- Coûts
    liv.salaire_par_livraison AS cout_chauffeur,
    l.cout_vehicule,
    (liv.salaire_par_livraison + l.cout_vehicule) AS cout_revient_total,
    
    -- Bénéfice
    (((c.poids_kg * c.prix_par_kg) * (1 + z.pourcentage_supplement / 100)) - 
     (liv.salaire_par_livraison + l.cout_vehicule)) AS benefice,
    
    -- Informations complémentaires
    CONCAT(liv.nom, ' ', liv.prenom) AS livreur,
    v.immatriculation,
    z.nom_zone

FROM livraisons_liv l
JOIN colis_liv c ON l.id_colis = c.id_colis
JOIN livreurs_liv liv ON l.id_livreur = liv.id_livreur
JOIN vehicules_liv v ON l.id_vehicule = v.id_vehicule
JOIN zones_livraison_liv z ON l.id_zone = z.id_zone
WHERE l.statut != 'annule';


ALTER TABLE livraisons_liv ADD INDEX idx_composite (statut, date_creation);
ALTER TABLE colis_liv ADD INDEX idx_poids (poids_kg);
ALTER TABLE livreurs_liv ADD INDEX idx_salaire (salaire_par_livraison);


SHOW TABLES;

DESC vue_benefices_livraisons_liv;


-- ============================================
-- PARTIE 2 - INSERTION DES DONNÉES DE TEST
-- ============================================

-- 1. INSÉRER 10 VÉHICULES
INSERT INTO vehicules_liv (immatriculation, marque, modele, annee, statut) VALUES
('1234-AAA', 'Toyota', 'Hiace', 2020, 'disponible'),
('5678-BBB', 'Isuzu', 'D-Max', 2019, 'disponible'),
('9012-CCC', 'Mitsubishi', 'L300', 2021, 'disponible'),
('3456-DDD', 'Nissan', 'NV350', 2018, 'disponible'),
('7890-EEE', 'Ford', 'Ranger', 2022, 'disponible'),
('1111-FFF', 'Mazda', 'BT-50', 2020, 'disponible'),
('2222-GGG', 'Toyota', 'Hilux', 2021, 'disponible'),
('3333-HHH', 'Isuzu', 'NLR', 2019, 'disponible'),
('4444-III', 'Hyundai', 'H100', 2020, 'disponible'),
('5555-JJJ', 'Mitsubishi', 'Fuso', 2022, 'disponible');

-- 2. INSÉRER 12 LIVREURS/CHAUFFEURS
-- 5 livreurs à 15000 Ar/livraison
INSERT INTO livreurs_liv (nom, prenom, telephone, salaire_par_livraison, statut) VALUES
('Rakoto', 'Jean', '0340001111', 15000, 'actif'),
('Rabe', 'Paul', '0340001112', 15000, 'actif'),
('Rasoa', 'Marie', '0340001113', 15000, 'actif'),
('Randria', 'Luc', '0340001114', 15000, 'actif'),
('Rakotozafy', 'Pierre', '0340001115', 15000, 'actif');

-- 3 livreurs à 18000 Ar/livraison
INSERT INTO livreurs_liv (nom, prenom, telephone, salaire_par_livraison, statut) VALUES
('Andria', 'Marc', '0340002221', 18000, 'actif'),
('Rasoamanana', 'Sophie', '0340002222', 18000, 'actif'),
('Rajao', 'David', '0340002223', 18000, 'actif');

-- 4 livreurs à 20000 Ar/livraison
INSERT INTO livreurs_liv (nom, prenom, telephone, salaire_par_livraison, statut) VALUES
('Razafi', 'Thomas', '0340003331', 20000, 'actif'),
('Ramanantsoa', 'Julie', '0340003332', 20000, 'actif'),
('Raharison', 'Michel', '0340003333', 20000, 'actif'),
('Rasolofo', 'Anne', '0340003334', 20000, 'actif');

-- 3. INSÉRER 5 ZONES DE LIVRAISON
-- 3 zones à 12,5%
INSERT INTO zones_livraison_liv (nom_zone, pourcentage_supplement, description) VALUES
('Antananarivo Centre', 12.5, 'Centre-ville, accès difficile'),
('Ivato', 12.5, 'Zone aéroportuaire'),
('Ankorondrano', 12.5, 'Zone commerciale dense');

-- 2 zones à 0%
INSERT INTO zones_livraison_liv (nom_zone, pourcentage_supplement, description) VALUES
('Ambohimanarina', 0, 'Zone périphérique facile'),
('Anosizato', 0, 'Zone industrielle accessible');

-- 4. INSÉRER L'ENTREPÔT UNIQUE
INSERT INTO entrepot_liv (nom, adresse, telephone) VALUES
('Entrepôt Principal', 'Andraharo, Antananarivo', '0340000000');

-- ============================================
-- DONNÉES DE TEST : QUELQUES LIVRAISONS EXEMPLE
-- ============================================

-- Exemple 1: Livraison en attente
INSERT INTO colis_liv (reference, poids_kg, prix_par_kg, description) VALUES
('COL-2025-001', 25.5, 5000, 'Colis alimentaire');

INSERT INTO livraisons_liv (id_colis, id_livreur, id_vehicule, id_entrepot, id_zone, 
                             adresse_destination, cout_vehicule, statut) 
VALUES (LAST_INSERT_ID(), 1, 1, 1, 1, 'Rue Rainibetsimisaraka, Analakely', 30000, 'en_attente');

-- Exemple 2: Livraison livrée
INSERT INTO colis_liv (reference, poids_kg, prix_par_kg, description) VALUES
('COL-2025-002', 15.0, 8000, 'Matériel informatique');

INSERT INTO livraisons_liv (id_colis, id_livreur, id_vehicule, id_entrepot, id_zone, 
                             adresse_destination, cout_vehicule, statut, date_livraison) 
VALUES (LAST_INSERT_ID(), 6, 2, 1, 4, 'Avenue de l''Indépendance, Ambohimanarina', 25000, 'livre', NOW());

-- Exemple 3: Livraison en cours
INSERT INTO colis_liv (reference, poids_kg, prix_par_kg, description) VALUES
('COL-2025-003', 40.0, 3000, 'Marchandises diverses');

INSERT INTO livraisons_liv (id_colis, id_livreur, id_vehicule, id_entrepot, id_zone, 
                             adresse_destination, cout_vehicule, statut) 
VALUES (LAST_INSERT_ID(), 9, 5, 1, 2, 'Zone Cargo, Ivato', 50000, 'en_cours');

-- ============================================
-- VÉRIFICATION DES DONNÉES
-- ============================================

-- Compter les véhicules
SELECT 'Véhicules' AS Type, COUNT(*) AS Nombre FROM vehicules_liv;

-- Compter les livreurs par catégorie de salaire
SELECT 
    salaire_par_livraison,
    COUNT(*) AS nombre_livreurs
FROM livreurs_liv
GROUP BY salaire_par_livraison
ORDER BY salaire_par_livraison;

-- Compter les zones par type de supplément
SELECT 
    pourcentage_supplement,
    COUNT(*) AS nombre_zones
FROM zones_livraison_liv
GROUP BY pourcentage_supplement
ORDER BY pourcentage_supplement;