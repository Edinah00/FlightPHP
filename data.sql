drop database TaxiBe;
create database TaxiBe;
use database Taxibe;

CREATE TABLE Chauffeur(
    id INT PRIMARY KEY, 
    nom VARCHAR(100));
CREATE TABLE Vehicule(
    id INT PRIMARY KEY, 
    immatriculation VARCHAR(20));
CREATE TABLE Trajet(
    id INT PRIMARY KEY,
    vehicule_id INT,
    chauffeur_id INT,
    pointA VARCHAR(100),
    pointB VARCHAR(100),
    distanceAB DECIMAL(10,2),
    date_Debut DATETIME,
    date_fin DATETIME,
    montant_recette DECIMAL(10,2),
    montant_carburant DECIMAL(10,2),
    FOREIGN KEY (vehicule_id) REFERENCES Vehicule(id),
    FOREIGN KEY (chauffeur_id) REFERENCES Chauffeur(id)
);
INSERT INTO Chauffeur (id, nom) VALUES 
(1, 'John Doe'),
(2, 'Jane Smith');
INSERT INTO Vehicule (id, immatriculation) VALUES 
(1, 'ABC-123'),
(2, 'XYZ-789');
INSERT INTO Trajet (id, vehicule_id, chauffeur_id, pointA, pointB, distanceAB, date_Debut, date_fin, montant_recette, montant_carburant) VALUES 
(1, 1, 1, 'Andoharanofotsy', 'Timbazaza', 15.5, '2024-01-01 08:00:00', '2024-01-01 08:30:00', 50.00, 10.00),
(2, 2, 2, 'Ambanidia', 'Ankadimbahoaka', 25.0, '2024-01-02 09:00:00', '2024-01-02 09:45:00', 80.00, 15.00);                  