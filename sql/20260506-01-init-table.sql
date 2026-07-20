CREATE DATABASE regime_alimentaire;

use regime_alimentaire;

CREATE TABLE statut_user(
    id_statut INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50)
);

CREATE TABLE user(
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    id_statut INT,
    FOREIGN KEY (id_statut) REFERENCES statut_user(id_statut)
);


