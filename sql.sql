-- Table membre
CREATE TABLE gestion_emprunt_membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    date_naissance DATE,
    genre ENUM('M','F'),
    email VARCHAR(100),
    ville VARCHAR(50),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);

-- Table categorie_objet
CREATE TABLE gestion_emprunt_categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50)
);

-- Table objet
CREATE TABLE gestion_emprunt_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES gestion_emprunt_categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES gestion_emprunt_membre(id_membre)
);

-- Table images_objet
CREATE TABLE gestion_emprunt_images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES gestion_emprunt_objet(id_objet)
);

-- Table emprunt
CREATE TABLE gestion_emprunt_emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES gestion_emprunt_objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES gestion_emprunt_membre(id_membre)
);

-- Insérer les membres
INSERT INTO gestion_emprunt_membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '1990-05-15', 'F', 'alice@example.com', 'Paris', 'mdp1', 'alice.jpg'),
('Bob', '1985-08-20', 'M', 'bob@example.com', 'Lyon', 'mdp2', 'bob.jpg'),
('Charlie', '1992-12-10', 'M', 'charlie@example.com', 'Marseille', 'mdp3', 'charlie.jpg'),
('Diana', '1995-03-05', 'F', 'diana@example.com', 'Toulouse', 'mdp4', 'diana.jpg');

-- Insérer les catégories
INSERT INTO gestion_emprunt_categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');

-- Insérer 10 objets par membre (répartis sur les catégories)
-- Exemple pour Alice
INSERT INTO gestion_emprunt_objet (nom_objet, id_categorie, id_membre) VALUES
('Peigne', 1, 1),
('Séchoir', 1, 1),
('Rouge à lèvres', 1, 1),
('Tournevis', 2, 1),
('Perceuse', 2, 1),
('Clé à molette', 3, 1),
('Pompe', 3, 1),
('Poêle', 4, 1),
('Casserole', 4, 1),
('Mixeur', 4, 1);

-- Exemple pour Bob
INSERT INTO gestion_emprunt_objet (nom_objet, id_categorie, id_membre) VALUES
('Brosse', 1, 2),
('Crème', 1, 2),
('Marteau', 2, 2),
('Scie', 2, 2),
('Pince', 2, 2),
('Clé anglaise', 3, 2),
('Crics', 3, 2),
('Friteuse', 4, 2),
('Couteau', 4, 2),
('Blender', 4, 2);

-- Exemple pour Charlie
INSERT INTO gestion_emprunt_objet (nom_objet, id_categorie, id_membre) VALUES
('Lisseur', 1, 3),
('Gel', 1, 3),
('Mastic', 2, 3),
('Perforateur', 2, 3),
('Scie sauteuse', 2, 3),
('Courroie', 3, 3),
('Batterie voiture', 3, 3),
('Four', 4, 3),
('Cafetière', 4, 3),
('Robot pâtissier', 4, 3);

-- Exemple pour Diana
INSERT INTO gestion_emprunt_objet (nom_objet, id_categorie, id_membre) VALUES
('Mascara', 1, 4),
('Fard à paupières', 1, 4),
('Mètre', 2, 4),
('Burineur', 2, 4),
('Pistolet à colle', 2, 4),
('Amortisseur', 3, 4),
('Jante', 3, 4),
('Mixeur plongeant', 4, 4),
('Poêle wok', 4, 4),
('Grille-pain', 4, 4);

-- Insérer quelques images d'objet pour illustration
INSERT INTO gestion_emprunt_images_objet (id_objet, nom_image) VALUES
(1, 'peigne1.jpg'),
(2, 'sechoir1.jpg'),
(3, 'rouge1.jpg');

-- Insérer 10 emprunts
INSERT INTO gestion_emprunt_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-07-01', '2025-07-10'),
(5, 3, '2025-07-02', '2025-07-12'),
(12, 1, '2025-07-03', '2025-07-13'),
(15, 4, '2025-07-04', '2025-07-14'),
(21, 2, '2025-07-05', '2025-07-15'),
(25, 3, '2025-07-06', '2025-07-16'),
(31, 1, '2025-07-07', '2025-07-17'),
(35, 4, '2025-07-08', '2025-07-18'),
(39, 2, '2025-07-09', '2025-07-19'),
(40, 1, '2025-07-10', '2025-07-20');

CREATE OR REPLACE VIEW v_objets_emprunt AS 
SELECT 
    o.id_objet,
    o.nom_objet,
    o.id_categorie,
    e.date_emprunt,
    e.date_retour,
    e.id_membre AS id_membre_emprunt,
    m.nom AS nom_membre
FROM 
    gestion_emprunt_objet o
LEFT JOIN 
    gestion_emprunt_emprunt e ON o.id_objet = e.id_objet
LEFT JOIN 
    gestion_emprunt_membre m ON e.id_membre = m.id_membre;

CREATE OR REPLACE VIEW vue_objet_details AS
SELECT 
    o.id_objet,
    o.nom_objet,
    o.id_categorie,
    c.nom_categorie
FROM gestion_emprunt_objet o
LEFT JOIN gestion_emprunt_categorie_objet c ON o.id_categorie = c.id_categorie;

CREATE OR REPLACE VIEW vue_images_objet AS
SELECT 
    id_objet,
    nom_image
FROM gestion_emprunt_images_objet;

CREATE OR REPLACE VIEW vue_historique_emprunts AS
SELECT 
    e.id_objet,
    m.nom AS nom_membre,
    e.date_emprunt,
    e.date_retour
FROM gestion_emprunt_emprunt e
JOIN gestion_emprunt_membre m ON e.id_membre = m.id_membre;

CREATE OR REPLACE VIEW vue_membre_objet_emprunt AS
SELECT 
    m.id_membre,
    m.nom,
    o.id_objet,
    o.nom_objet,
    c.id_categorie,
    c.nom_categorie,
    e.date_emprunt,
    e.date_retour
FROM gestion_emprunt_membre m
LEFT JOIN gestion_emprunt_objet o ON o.id_membre = m.id_membre
LEFT JOIN gestion_emprunt_categorie_objet c ON o.id_categorie = c.id_categorie
LEFT JOIN gestion_emprunt_emprunt e ON e.id_objet = o.id_objet
;
ALTER TABLE gestion_emprunt_emprunt ADD COLUMN etat_retour VARCHAR(10) DEFAULT NULL;
