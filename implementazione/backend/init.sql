CREATE SCHEMA IF NOT EXISTS centro_sportivo;

SET search_path TO centro_sportivo;

CREATE TABLE utenti (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(10) CHECK (role IN ('user', 'admin')) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO utenti (name, email, password, role) VALUES
('user', 'user@example.com', '$2y$12$R/iWKtz2NU8K7iNUL5eCF.wY2OIMkXfx9dQA/EQ9ZwVtCbKQf.HFa', 'user'),
('user2', 'user2@example.com', '$2y$12$R/iWKtz2NU8K7iNUL5eCF.wY2OIMkXfx9dQA/EQ9ZwVtCbKQf.HFa', 'user'),
('admin', 'admin@example.com', '$2y$12$3O8rjehVFE0AAspZMS3C8O8ltLQgeYaaT8Zl17jeeBDv7u5GXfWMq', 'admin');

CREATE TABLE campi (
    id SERIAL PRIMARY KEY,
    sport VARCHAR(50) NOT NULL,
    price NUMERIC(10,2) NOT NULL
);

INSERT INTO campi (sport, price) VALUES
('calcio', 50.00),
('tennis', 30.00),
('basket', 40.00),
('padel', 35.00),
('volley', 45.00);

CREATE TABLE corsi (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price NUMERIC(10,2) NOT NULL,
    capacity INT NOT NULL CHECK (capacity > 0)
);

INSERT INTO corsi (name, description, price, capacity) VALUES
('Corso Padel Base', 'Introduzione al padel', 90.00, 18),
('Corso Volley Avanzato', 'Volley per esperti', 160.00, 12),
('Corso Fitness Generale', 'Corso di fitness completo', 70.00, 30),
('Corso Yoga', 'Sessione di yoga rilassante', 60.00, 20),
('Corso Boxe', 'Corso di boxe per principianti', 110.00, 15);

CREATE TABLE orari_corsi (
    id SERIAL PRIMARY KEY,
    corso_id INT NOT NULL REFERENCES corsi(id) ON DELETE CASCADE,
    orario TIME NOT NULL
);

INSERT INTO orari_corsi (corso_id, orario) VALUES
(1, '09:00'), (1, '11:00'), (1, '15:00'), -- Padel Base
(2, '18:00'), (2, '20:00'), -- Volley
(3, '08:00'), (3, '10:00'), (3, '13:00'), (3, '19:00'), -- Fitness
(4, '07:00'), (4, '19:30'), -- Yoga
(5, '17:00'), (5, '19:00'); -- Boxe

CREATE TABLE prenotazioni (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES utenti(id) ON DELETE CASCADE,
    tipo VARCHAR(10) NOT NULL CHECK (tipo IN ('campo', 'corso')),
    campo_id INT REFERENCES campi(id),
    corso_id INT REFERENCES corsi(id),
    data DATE NOT NULL,
    slot_start TIME NOT NULL,
    stato VARCHAR(15) DEFAULT 'confermata' CHECK (stato IN ('carrello', 'confermata', 'cancellata')),
    quantity INT DEFAULT 1,
    CHECK (
        (tipo = 'campo' AND campo_id IS NOT NULL AND corso_id IS NULL)
        OR
        (tipo = 'corso' AND corso_id IS NOT NULL AND campo_id IS NULL)
    )
);

INSERT INTO prenotazioni (user_id, tipo, campo_id, data, slot_start, stato, quantity) VALUES
(1, 'campo', 2, '2026-01-22', '10:00', 'confermata', 1),
(1, 'campo', 2, '2026-01-23', '14:00', 'confermata', 1),
(1, 'campo', 1, '2026-01-23', '14:00', 'confermata', 1),
(2, 'campo', 1, '2026-01-23', '14:30', 'confermata', 1),
(2, 'campo', 3, '2026-01-24', '09:00', 'confermata', 1),
(1, 'campo', 4, '2026-01-25', '11:00', 'confermata', 1);

INSERT INTO prenotazioni (user_id, tipo, corso_id, data, slot_start, stato, quantity) VALUES
(1, 'corso', 2, '2026-01-26', '16:00', 'confermata', 1),
(2, 'corso', 2, '2026-01-26', '16:00', 'confermata', 1),
(1, 'corso', 2, '2026-01-26', '14:00', 'confermata', 1),
(3, 'corso', 1, '2026-01-26', '16:00', 'confermata', 1);

CREATE UNIQUE INDEX unique_campo_data_slot
ON prenotazioni (campo_id, data, slot_start)
WHERE tipo = 'campo' AND stato IN ('confermata', 'carrello');

CREATE TABLE abbonamenti (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    prezzo NUMERIC(10,2) NOT NULL CHECK (prezzo >= 0),
    durata_giorni INT NOT NULL CHECK (durata_giorni > 0),
    descrizione TEXT
);

INSERT INTO abbonamenti (nome, prezzo, durata_giorni, descrizione) VALUES
('Abbonamento Mensile', 50.00, 30, 'Abbonamento base mensile'),
('Abbonamento Trimestrale', 140.00, 90, 'Abbonamento trimestrale con sconto'),
('Abbonamento Annuale', 500.00, 365, 'Abbonamento annuale conveniente'),
('Abbonamento VIP', 1000.00, 365, 'Abbonamento VIP con servizi extra'),
('Abbonamento Giornaliero', 10.00, 1, 'Abbonamento per un giorno');

CREATE TABLE abbonamenti_utenti (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES utenti(id) ON DELETE CASCADE,
    abbonamento_id INT NOT NULL REFERENCES abbonamenti(id),
    data_inizio DATE NOT NULL,
    data_fine DATE NOT NULL,
    attivo BOOLEAN DEFAULT true,
    CHECK (data_fine > data_inizio)
);

INSERT INTO abbonamenti_utenti (user_id, abbonamento_id, data_inizio, data_fine) VALUES
(1, 1, '2026-01-22', '2026-02-21'),
(2, 2, '2026-01-22', '2026-04-22');

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

INSERT INTO pagamenti (user_id, totale, tipo) VALUES
(1, 50.00, 'abbonamento'),
(2, 140.00, 'abbonamento'),
(1, 30.00, 'prenotazione'),
(1, 30.00, 'prenotazione'),
(1, 50.00, 'prenotazione'),
(2, 50.00, 'prenotazione'),
(2, 40.00, 'prenotazione'),
(1, 35.00, 'prenotazione'),
(1, 160.00, 'prenotazione'),
(2, 160.00, 'prenotazione'),
(1, 160.00, 'prenotazione'),
(3, 90.00, 'prenotazione');

CREATE TABLE pagamenti_abbonamenti (
    pagamento_id INT PRIMARY KEY REFERENCES pagamenti(id) ON DELETE CASCADE,
    abbonamento_utente_id INT NOT NULL REFERENCES abbonamenti_utenti(id)
);

INSERT INTO pagamenti_abbonamenti (pagamento_id, abbonamento_utente_id) VALUES
(1, 1),
(2, 2);

CREATE TABLE pagamenti_prenotazioni (
    pagamento_id INT NOT NULL REFERENCES pagamenti(id) ON DELETE CASCADE,
    prenotazione_id INT NOT NULL REFERENCES prenotazioni(id) ON DELETE CASCADE,
    PRIMARY KEY (pagamento_id, prenotazione_id)
);

INSERT INTO pagamenti_prenotazioni (pagamento_id, prenotazione_id) VALUES
(3, 1),
(4, 2),
(5, 3),
(6, 4),
(7, 5),
(8, 6),
(9, 7),
(10, 8),
(11, 9),
(12, 10);
