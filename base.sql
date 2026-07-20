CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(3) NOT NULL UNIQUE,
    operateur VARCHAR(50) NOT NULL
);

CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE role (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    id_role INTEGER NOT NULL,
    FOREIGN KEY (id_role) REFERENCES role(id)
);

CREATE TABLE numero_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_prefixe INTEGER NOT NULL,
    numero VARCHAR(20) NOT NULL UNIQUE,
    id_user INTEGER NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prefixe) REFERENCES operateur(id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    intervalle1 DECIMAL(10,2) NOT NULL,
    intervalle2 DECIMAL(10,2) NOT NULL,
    frais DECIMAL(10,2) NOT NULL,
    id_operateur INTEGER NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id),
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);

CREATE TABLE historique_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_user INTEGER NOT NULL,
    id_operation INTEGER NOT NULL,
    valeur DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_operation) REFERENCES type_operation(id)
);