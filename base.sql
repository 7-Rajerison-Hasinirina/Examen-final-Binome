

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(3) NOT NULL UNIQUE,
    operateur VARCHAR(50) NOT NULL
);

CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE numero (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE role (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    id_role INTEGER NOT NULL,
    FOREIGN KEY (id_role) REFERENCES role(id)
);

CREATE TABLE numero_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur INTEGER NOT NULL,
    id_numero INTEGER NOT NULL,
    id_user INTEGER NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id),
    FOREIGN KEY (id_numero) REFERENCES numero(id),
    FOREIGN KEY (id_user) REFERENCES user(id)
);

CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    intervalle1 DECIMAL(10,2) NOT NULL,
    intervalle2 DECIMAL(10,2) NOT NULL,
    frais DECIMAL(10,2) NOT NULL,
    id_operateur INTEGER NOT NULL,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE historique_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_type_operation INTEGER NOT NULL,
    id_expediteur INTEGER NOT NULL,
    id_destinataire INTEGER,
    valeur DECIMAL(10,2) NOT NULL,
    frais DECIMAL(10,2) DEFAULT 0,
    observation TEXT,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id),
    FOREIGN KEY (id_expediteur) REFERENCES user(id),
    FOREIGN KEY (id_destinataire) REFERENCES user(id)
);