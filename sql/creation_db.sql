CREATE DATABASE IF NOT EXISTS monprojet;
USE monprojet;

-- Table activites

DROP TABLE IF EXISTS activites;
CREATE TABLE IF NOT EXISTS activites (
  idActivite int NOT NULL AUTO_INCREMENT,
  mailOrganisateur varchar(50) NOT NULL,
  typeActivite varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  commune varchar(30) NOT NULL,
  descriptif varchar(255) NOT NULL,
  date date NOT NULL,
  longitude decimal(10,8) DEFAULT NULL,
  latitude decimal(10,8) DEFAULT NULL,
  PRIMARY KEY (idActivite),
  FOREIGN KEY (mailOrganisateur) REFERENCES utilisateurs (Adressemail),
  FOREIGN KEY (typeActivite) REFERENCES typesactivite (typeActivite)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table typesactivite
DROP TABLE IF EXISTS typesactivite;
CREATE TABLE IF NOT EXISTS typesactivite (
  typeActivite varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  activiteGenerique varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (typeActivite)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Table utilisateurs
DROP TABLE IF EXISTS utilisateurs;
CREATE TABLE IF NOT EXISTS utilisateurs (
  Adressemail varchar(50) NOT NULL,
  nom varchar(30) NOT NULL,
  prenom varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  telephone varchar(30) NOT NULL,
  PRIMARY KEY (Adressemail)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;