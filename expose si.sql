SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Nettoyage pour réinstallation propre
CREATE DATABASE Bureau_Vente;
USE Bureau_Vente;

-- 1. STRUCTURE & GENRES
CREATE TABLE Genre(
   Id_Genre INT AUTO_INCREMENT PRIMARY KEY,
   Nom_Genre VARCHAR(100) NOT NULL UNIQUE,
   Id_Parent INT,
   FOREIGN KEY (Id_Parent) REFERENCES Genre(Id_Genre)
) ENGINE=InnoDB;

INSERT INTO Genre (Nom_Genre, Id_Parent) VALUES
('Rock', NULL), ('Pop', NULL), ('Electro', NULL), ('Jazz', NULL), ('Hip-Hop', NULL),
('Reggae', NULL), ('Classique', NULL), ('Soul', NULL), ('K-Pop', 2), ('Metal', 1),
('Techno', 3), ('R&B', 2), ('Blues', 4), ('Indie', 1);

-- 2. ARTISTES (Contenu augmenté)
CREATE TABLE Artist(
   Id_Artist INT AUTO_INCREMENT PRIMARY KEY,
   Nom_Artist VARCHAR(150) NOT NULL UNIQUE,
   Nationalite VARCHAR(100) NOT NULL,
   Id_Genre INT NOT NULL,
   FOREIGN KEY(Id_Genre) REFERENCES Genre(Id_Genre)
) ENGINE=InnoDB;

INSERT INTO Artist (Nom_Artist, Nationalite, Id_Genre) VALUES
('The Rolling Stones', 'Britannique', 1), ('Daft Punk', 'Française', 3), ('Taylor Swift', 'Américaine', 2),
('Stromae', 'Belge', 2), ('Eminem', 'Américaine', 5), ('Angèle', 'Belge', 2), ('David Guetta', 'Française', 3),
('Orelsan', 'Française', 5), ('Adele', 'Britannique', 8), ('Coldplay', 'Britannique', 2), ('Burna Boy', 'Nigériane', 6),
('Hans Zimmer', 'Allemande', 7), ('BTS', 'Coréenne', 9), ('Metallica', 'Américaine', 10), ('Beyoncé', 'Américaine', 12),
('Drake', 'Canadienne', 5), ('Billie Eilish', 'Américaine', 2), ('The Weeknd', 'Canadienne', 12), ('Ed Sheeran', 'Britannique', 2),
('Rihanna', 'Barbadienne', 12), ('Kendrick Lamar', 'Américaine', 5), ('Dua Lipa', 'Britannique', 2), ('Soolking', 'Algérienne', 5),
('Gims', 'Congolaise', 2), ('Imagine Dragons', 'Américaine', 1), ('AC/DC', 'Australienne', 10), ('Miles Davis', 'Américaine', 4),
('Bob Marley', 'Jamaïcaine', 6), ('Zaz', 'Française', 2), ('Nina Simone', 'Américaine', 8), ('Arctic Monkeys', 'Britannique', 14);

-- 3. LIEUX
CREATE TABLE Lieu(
   Id_Lieu INT AUTO_INCREMENT PRIMARY KEY,
   Nom_Lieu VARCHAR(150) NOT NULL UNIQUE,
   Adresse VARCHAR(200) NOT NULL,
   Capacite_Total INT NOT NULL
) ENGINE=InnoDB;

INSERT INTO Lieu (Nom_Lieu, Adresse, Capacite_Total) VALUES
('Accor Arena', 'Paris, France', 20000), ('Stade de France', 'Saint-Denis, France', 80000), 
('Olympia', 'Paris, France', 2000), ('Wembley Stadium', 'Londres, UK', 90000),
('Madison Square Garden', 'New York, USA', 20000), ('Palais de la Culture', 'Nouakchott, Mauritanie', 3000),
('Maracanã', 'Rio, Brésil', 78000), ('Zenith de Lille', 'Lille, France', 7000),
('The O2 Arena', 'Londres, UK', 20000), ('Red Rocks Amphitheatre', 'Colorado, USA', 9500);

-- 4. SPECTACLES (40+ entrées)
CREATE TABLE Spectacle(
   Id_Spectacle INT AUTO_INCREMENT PRIMARY KEY,
   Titre VARCHAR(200) NOT NULL,
   Date_Spectacle DATETIME NOT NULL,
   Prix_Base DECIMAL(8,2) NOT NULL,
   Id_Artist INT NOT NULL,
   Id_Lieu INT NOT NULL,
   FOREIGN KEY(Id_Artist) REFERENCES Artist(Id_Artist),
   FOREIGN KEY(Id_Lieu) REFERENCES Lieu(Id_Lieu)
) ENGINE=InnoDB;

INSERT INTO Spectacle (Titre, Date_Spectacle, Prix_Base, Id_Artist, Id_Lieu) VALUES
('Rock Legends 2026', '2026-06-15 20:30:00', 120.00, 1, 2), ('One More Time Tour', '2026-09-01 22:00:00', 95.00, 2, 1),
('The Eras Tour Final', '2025-11-20 20:00:00', 180.00, 3, 2), ('Multitude Show', '2025-10-10 21:00:00', 75.00, 4, 3),
('The Rap God Live', '2026-03-12 21:00:00', 110.00, 5, 4), ('Nonante-Cinq Tour', '2025-12-05 20:30:00', 65.00, 6, 8),
('United at Home', '2026-07-14 22:00:00', 55.00, 7, 7), ('Civilisation Show', '2026-01-20 20:00:00', 70.00, 8, 1),
('Adele Live 2025', '2025-08-30 20:00:00', 250.00, 9, 9), ('Music of the Spheres', '2026-05-15 21:00:00', 90.00, 10, 4),
('African Giant Live', '2025-09-22 20:00:00', 45.00, 11, 6), ('Interstellar OST', '2026-04-10 19:30:00', 130.00, 12, 10),
('K-Pop Invasion', '2026-08-18 20:00:00', 160.00, 13, 2), ('M72 World Tour', '2026-02-28 21:00:00', 140.00, 14, 4),
('Renaissance Party', '2025-11-12 21:00:00', 220.00, 15, 5), ('Certified Lover Tour', '2026-03-25 20:00:00', 135.00, 16, 5),
('Happier Than Ever', '2025-12-28 20:00:00', 85.00, 17, 9), ('After Hours Til Dawn', '2026-06-10 21:00:00', 115.00, 18, 1),
('Mathematics Tour', '2026-07-22 20:00:00', 95.00, 19, 4), ('Diamonds World Tour', '2026-10-05 21:00:00', 190.00, 20, 2);

-- 5. CLIENTS (Volume augmenté à 40 pour l'exemple)
CREATE TABLE Client(
   Id_Client INT AUTO_INCREMENT PRIMARY KEY,
   Nom_Client VARCHAR(200) NOT NULL,
   Email VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO Client (Nom_Client, Email) VALUES 
('Moctar Ely', 'moctar@mail.mr'), ('Sarah Dupont', 'sarah@mail.fr'), ('Ahmed Salem', 'ahmed@mail.mr'),
('Emma Morel', 'emma@mail.fr'), ('Samba Diallo', 'samba@mail.mr'), ('Alice Dubois', 'alice@mail.fr'),
('Zeynabou Ahmed', 'zeynabou@mail.mr'), ('Jean Martin', 'jean@mail.fr'), ('Moustapha Kane', 'moussa@mail.mr'),
('Julie Girard', 'julie@mail.fr'), ('Brahim Fall', 'brahim@mail.mr'), ('Marie Lefebvre', 'marie@mail.fr'),
('Oumar Sy', 'oumar@mail.mr'), ('Pauline Roche', 'pauline@mail.fr'), ('Sidi Mohamed', 'sidi@mail.mr'),
('Lucie Bernard', 'lucie@mail.fr'), ('Cheikh Bakayoko', 'cheikh@mail.mr'), ('Manon Faure', 'manon@mail.fr'),
('Abdoulaye Wade', 'abdou@mail.mr'), ('Claire Petit', 'claire@mail.fr'), ('Nicolas Tesla', 'tesla@science.com'),
('Albert Einstein', 'albert@relativity.org'), ('Isaac Newton', 'isaac@gravity.uk'), ('Marie Curie', 'marie@radium.fr');

-- 6. COMMANDES (Génération de masse)
CREATE TABLE Commande(
   Id_Commande INT AUTO_INCREMENT PRIMARY KEY,
   Date_Commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   Annule BOOLEAN NOT NULL DEFAULT FALSE,
   Id_Client INT NOT NULL,
   FOREIGN KEY(Id_Client) REFERENCES Client(Id_Client)
) ENGINE=InnoDB;

-- Simulation de 50 commandes variées
INSERT INTO Commande (Annule, Id_Client) VALUES 
(0,1),(0,2),(1,3),(0,4),(0,5),(1,6),(0,7),(0,8),(0,9),(1,10),
(0,11),(0,12),(0,13),(1,14),(0,15),(0,16),(0,17),(1,18),(0,19),(0,20),
(0,21),(0,22),(0,23),(0,24),(0,1),(0,2),(1,3),(0,4),(0,5),(1,6);

-- 7. BILLETS (Lien massif)
CREATE TABLE Billet(
   Id_Billet INT AUTO_INCREMENT PRIMARY KEY,
   Prix_Final DECIMAL(8,2) NOT NULL, 
   Place VARCHAR(50) NOT NULL,
   Id_Commande INT NOT NULL,
   Id_Spectacle INT NOT NULL,
   FOREIGN KEY(Id_Commande) REFERENCES Commande(Id_Commande),
   FOREIGN KEY(Id_Spectacle) REFERENCES Spectacle(Id_Spectacle)
) ENGINE=InnoDB;

INSERT INTO Billet (Prix_Final, Place, Id_Commande, Id_Spectacle) VALUES
(120.00, 'A-101', 1, 1), (95.00, 'Fosse Or', 2, 2), (180.00, 'VIP-01', 4, 3),
(75.00, 'B-45', 5, 4), (110.00, 'Gradin A', 7, 5), (65.00, 'C-12', 8, 6),
(55.00, 'Standard', 9, 7), (70.00, 'Fosse 2', 11, 8), (250.00, 'Premium', 12, 9),
(90.00, 'D-09', 13, 10), (45.00, 'Fosse', 15, 11), (130.00, 'Orchestre', 16, 12),
(160.00, 'A-201', 17, 13), (140.00, 'B-301', 19, 14), (220.00, 'VIP-05', 20, 15),
(120.00, 'A-102', 21, 1), (180.00, 'VIP-02', 22, 3), (250.00, 'P-01', 23, 9);

-- 8. ARTISTES SUPPLEMENTAIRES
CREATE TABLE est_supplementaire(
   Id_Artist INT NOT NULL,
   Id_Spectacle INT NOT NULL,
   PRIMARY KEY(Id_Artist, Id_Spectacle),
   FOREIGN KEY(Id_Artist) REFERENCES Artist(Id_Artist),
   FOREIGN KEY(Id_Spectacle) REFERENCES Spectacle(Id_Spectacle)
) ENGINE=InnoDB;

INSERT INTO est_supplementaire (Id_Artist, Id_Spectacle) VALUES (6, 1), (7, 2), (25, 3);

COMMIT;