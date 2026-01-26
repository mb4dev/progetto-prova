CREATE DATABASE IF NOT EXISTS centro_sportivo;
\c centro_sportivo


CREATE TABLE utenti (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(10) CHECK (role IN ('user', 'admin')) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO utenti (name, email, password, role) VALUES ('user', 'user', '$2y$12$R/iWKtz2NU8K7iNUL5eCF.wY2OIMkXfx9dQA/EQ9ZwVtCbKQf.HFa', 'user');
INSERT INTO utenti (name, email, password, role) VALUES ('admin', 'admin', '$2y$12$3O8rjehVFE0AAspZMS3C8O8ltLQgeYaaT8Zl17jeeBDv7u5GXfWMq', 'admin');
INSERT INTO utenti (name, email, password, role) VALUES ('mario', 'mario@example.com', '$2y$12$dummyhash', 'user');
INSERT INTO utenti (name, email, password, role) VALUES ('luigi', 'luigi@example.com', '$2y$12$dummyhash2', 'user');


CREATE TABLE campi (
    id SERIAL PRIMARY KEY,
    sport VARCHAR(50) NOT NULL,
    price NUMERIC(10,2) NOT NULL
);


CREATE TABLE corsi (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price NUMERIC(10,2) NOT NULL,
    capacity INT NOT NULL CHECK (capacity > 0)
);


INSERT INTO campi (sport, price) VALUES ('calcio', 50.00);
INSERT INTO campi (sport, price) VALUES ('tennis', 30.00);
INSERT INTO campi (sport, price) VALUES ('basket', 40.00);
INSERT INTO campi (sport, price) VALUES ('nuoto', 25.00);

INSERT INTO corsi (name, description, price, capacity) VALUES ('Corso Calcio Base', 'Corso introduttivo al calcio per principianti', 100.00, 20);
INSERT INTO corsi (name, description, price, capacity) VALUES ('Corso Tennis Avanzato', 'Corso per giocatori esperti di tennis', 150.00, 10);
INSERT INTO corsi (name, description, price, capacity) VALUES ('Corso Basket Intermedio', 'Corso di basket per livello intermedio', 120.00, 15);
INSERT INTO corsi (name, description, price, capacity) VALUES ('Corso Nuoto Principiante', 'Corso base di nuoto', 80.00, 25);


CREATE TABLE prenotazioni (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES utenti(id) ON DELETE CASCADE,
    tipo VARCHAR(10) NOT NULL CHECK (tipo IN ('campo', 'corso')),
	campo_id INT REFERENCES campi(id),
    corso_id INT REFERENCES corsi(id),
    data DATE NOT NULL,
    slot_start TIME NOT NULL,

    CHECK (
        (tipo = 'campo' AND campo_id IS NOT NULL AND corso_id IS NULL)
        OR
        (tipo = 'corso' AND corso_id IS NOT NULL AND campo_id IS NULL)
    )
);

INSERT INTO prenotazioni (user_id, tipo, campo_id, data, slot_start) VALUES (1, 'campo', 1, '2024-01-01', '10:00:00');
INSERT INTO prenotazioni (user_id, tipo, campo_id, data, slot_start) VALUES (2, 'campo', 2, '2024-01-02', '14:00:00');
INSERT INTO prenotazioni (user_id, tipo, corso_id, data, slot_start) VALUES (3, 'corso', 1, '2024-01-03', '16:00:00');
INSERT INTO prenotazioni (user_id, tipo, corso_id, data, slot_start) VALUES (4, 'corso', 2, '2024-01-04', '18:00:00');


CREATE UNIQUE INDEX unique_campo_data_slot 
ON prenotazioni (campo_id, data, slot)
WHERE tipo = 'campo';


CREATE TABLE abbonamenti (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    prezzo NUMERIC(10,2) NOT NULL CHECK (prezzo >= 0),
    durata_giorni INT NOT NULL CHECK (durata_giorni > 0),
    descrizione TEXT
);

INSERT INTO abbonamenti (nome, prezzo, durata_giorni, descrizione) VALUES ('Abbonamento Mensile', 50.00, 30, 'Abbonamento base mensile');
INSERT INTO abbonamenti (nome, prezzo, durata_giorni, descrizione) VALUES ('Abbonamento Trimestrale', 140.00, 90, 'Abbonamento trimestrale con sconto');
INSERT INTO abbonamenti (nome, prezzo, durata_giorni, descrizione) VALUES ('Abbonamento Annuale', 500.00, 365, 'Abbonamento annuale conveniente');
INSERT INTO abbonamenti (nome, prezzo, durata_giorni, descrizione) VALUES ('Abbonamento VIP', 1000.00, 365, 'Abbonamento VIP con servizi extra');


CREATE TABLE abbonamenti_utenti (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES utenti(id) ON DELETE CASCADE,
    abbonamento_id INT NOT NULL REFERENCES abbonamenti(id),
    data_inizio DATE NOT NULL,
    data_fine DATE NOT NULL,
    CHECK (data_fine > data_inizio)
);

INSERT INTO abbonamenti_utenti (user_id, abbonamento_id, data_inizio, data_fine) VALUES (1, 1, '2024-01-01', '2024-02-01');
INSERT INTO abbonamenti_utenti (user_id, abbonamento_id, data_inizio, data_fine) VALUES (2, 2, '2024-01-01', '2024-04-01');
INSERT INTO abbonamenti_utenti (user_id, abbonamento_id, data_inizio, data_fine) VALUES (3, 3, '2024-01-01', '2025-01-01');
INSERT INTO abbonamenti_utenti (user_id, abbonamento_id, data_inizio, data_fine) VALUES (4, 4, '2024-01-01', '2025-01-01');

CREATE UNIQUE INDEX unique_abbonamento_attivo_per_utente
ON abbonamenti_utenti (user_id)
WHERE attivo = true;


CREATE TABLE pagamenti (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES utenti(id) ON DELETE CASCADE,
    totale NUMERIC(10,2) NOT NULL CHECK (totale >= 0),
    data_pagamento TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipo VARCHAR(15) NOT NULL CHECK (tipo IN ('abbonamento', 'prenotazione'))
);

INSERT INTO pagamenti (user_id, totale, tipo) VALUES (1, 50.00, 'abbonamento');
INSERT INTO pagamenti (user_id, totale, tipo) VALUES (2, 140.00, 'abbonamento');
INSERT INTO pagamenti (user_id, totale, tipo) VALUES (3, 100.00, 'prenotazione');
INSERT INTO pagamenti (user_id, totale, tipo) VALUES (4, 150.00, 'prenotazione');


CREATE TABLE pagamenti_abbonamenti (
    pagamento_id INT PRIMARY KEY REFERENCES pagamenti(id) ON DELETE CASCADE,
    abbonamento_utente_id INT NOT NULL REFERENCES abbonamenti_utenti(id)
);

INSERT INTO pagamenti_abbonamenti (pagamento_id, abbonamento_utente_id) VALUES (1, 1);
INSERT INTO pagamenti_abbonamenti (pagamento_id, abbonamento_utente_id) VALUES (2, 2);

CREATE TABLE pagamenti_prenotazioni (
    pagamento_id INT NOT NULL REFERENCES pagamenti(id) ON DELETE CASCADE,
    prenotazione_id INT NOT NULL REFERENCES prenotazioni(id) ON DELETE CASCADE,
    PRIMARY KEY (pagamento_id, prenotazione_id)
);

INSERT INTO pagamenti_prenotazioni (pagamento_id, prenotazione_id) VALUES (3, 3);
INSERT INTO pagamenti_prenotazioni (pagamento_id, prenotazione_id) VALUES (4, 4);
