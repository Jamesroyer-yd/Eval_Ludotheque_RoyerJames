CREATE DATABASE IF NOT EXISTS ludotheque CHARSET utf8mb4;
USE ludotheque;

CREATE TABLE IF NOT EXISTS categorie(
	id_categorie INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(50) NOT NULL UNIQUE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS membre(
	id_membre INT PRIMARY KEY AUTO_INCREMENT,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(100) NOT NULL,
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jeu(
	id_jeu INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(100) NOT NULL UNIQUE,
    joueurs_min INT NOT NULL,
    joueurs_max INT NOT NULL,
    duree INT NOT NULL,
    id_categorie INT NOT NULL,
    id_membre INT NOT NULL,
    CONSTRAINT fk_jeu_categorie FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie),
    CONSTRAINT fk_jeu_membre FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
)ENGINE=InnoDB;

INSERT INTO categorie (nom_categorie)
	VALUES ('Stratégie'),
		('Ambiance'),
        ('Coopératif'),
        ('Familial'),
        ('Enquête'),
        ('Abstrait');

-- Comptes de test : le mot de passe des deux comptes est Ludotheque2026!
INSERT INTO membre (prenom, nom, email, mot_de_passe)
	VALUES ('Camille', 'Martin', 'camille.martin@ludo.test', '$2y$10$zXCQ.T7QSOpIwqLboWZbw.PQqEuSk3dc/25pZURTjU2fckaook2B.'),
		('Hugo', 'Bernard', 'hugo.bernard@ludo.test', '$2y$10$/CpUCMvMxXG9V5Fw04yyweyadI3KE0Z5gwi9.CUOHIH4VPZkgwAWO');

-- duree : durée moyenne d'une partie, en minutes
INSERT INTO jeu (titre, joueurs_min, joueurs_max, duree, id_categorie, id_membre)
	VALUES ('Les Comptoirs du Levant', 2, 4, 90, 1, 1),
		('Mots en Cascade', 4, 10, 20, 2, 2),
        ('L\'Île aux Phares', 1, 4, 45, 3, 1),
        ('Petits Potagers', 2, 5, 30, 4, 2),
        ('Meurtre au Manoir Blackwood', 3, 6, 120, 5, 1),
        ('Duel de Mages', 2, 2, 15, 1, 2),
        ('La Grande Traversée', 2, 6, 180, 3, 1),
        ('Le Bal Masqué', 5, 12, 60, 2, 2),
        ('Carnet d\'Explorateur', 1, 1, 25, 1, 1);
