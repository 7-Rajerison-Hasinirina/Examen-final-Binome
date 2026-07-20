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
    numero_source VARCHAR(20),
    numero_destination VARCHAR(20),
    reference VARCHAR(255),
    sens VARCHAR(10),
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_operation) REFERENCES type_operation(id)
);



INSERT INTO operateur (prefixe, operateur) VALUES
('032', 'Orange Money'),
('033', 'Airtel Money'),
('034', 'MVola'),
('038', 'MVola');


INSERT INTO type_operation (libelle) VALUES
('Depot'),
('Retrait'),
('Transfert');


INSERT INTO role (libelle) VALUES
('Admin'),
('Client'),
('Operateur');


INSERT INTO users (nom, id_role) VALUES
('Jean Paul', 1),
('Rakoto Jean', 2),
('Rabe Marie', 2),
('Rasoa Aina', 2),
('Yas', 3);


INSERT INTO numero_user (id_prefixe, numero, id_user) VALUES
(2, '1111112', 2),
(3, '2222222', 2),
(4, '1234567', 3),
(1, '9876543', 4),
(4, '7654321', 5),
(2, '9999999', 1);


INSERT INTO bareme_frais (intervalle1, intervalle2, frais, id_operateur) VALUES
-- Orange Money
(100, 1000, 50, 1),
(1001, 5000, 100, 1),
(5001, 10000, 200, 1),
(10001, 50000, 500, 1),
-- Airtel Money
(100, 1000, 50, 2),
(1001, 5000, 100, 2),
(5001, 10000, 200, 2),
(10001, 50000, 500, 2),
-- MVola
(100, 1000, 50, 3),
(1001, 5000, 100, 3),
(5001, 10000, 200, 3),
(10001, 50000, 500, 3),
(100, 1000, 50, 4),
(1001, 5000, 100, 4),
(5001, 10000, 200, 4),
(10001, 50000, 500, 4);


INSERT INTO historique_operation (date, id_user, id_operation, valeur, numero_source, numero_destination, reference, sens) VALUES

('2026-07-20 08:15:00', 2, 1, 5000.00, NULL, '1111112', 'Dépôt initial', 'entree'),
('2026-07-20 09:00:00', 2, 1, 10000.00, NULL, '2222222', 'Dépôt initial', 'entree'),
('2026-07-20 10:20:00', 2, 3, 3000.00, '1111112', '2222222', 'Transfert interne', 'sortie'),
('2026-07-20 10:20:00', 2, 3, 3000.00, '1111112', '2222222', 'Transfert interne', 'entree'),
('2026-07-20 11:31:00', 2, 2, 5000.00, '1111112', NULL, 'Retrait guichet', 'sortie'),
('2026-07-20 13:10:00', 2, 2, 2500.00, '2222222', NULL, 'Retrait guichet', 'sortie'),
('2026-07-20 14:45:00', 3, 1, 15000.00, NULL, '1234567', 'Dépôt initial', 'entree'),
('2026-07-20 16:00:00', 3, 3, 7000.00, '1234567', '9876543', 'Transfert client', 'sortie'),
('2026-07-20 16:00:00', 4, 3, 7000.00, '1234567', '9876543', 'Transfert client', 'entree'),
('2026-07-20 17:30:00', 2, 1, 20000.00, NULL, '1111112', 'Dépôt bonus', 'entree');