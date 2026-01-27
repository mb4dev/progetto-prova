# Diagramma ER del Database Centro Sportivo

```mermaid
erDiagram
    utenti {
        int id PK
        varchar name
        varchar email
        varchar password
        varchar role
        timestamp created_at
    }

    campi {
        int id PK
        varchar sport
        numeric price
    }

    corsi {
        int id PK
        varchar name
        text description
        numeric price
        int capacity
    }

    orari_corsi {
        int id PK
        int corso_id FK
        time orario
    }

    prenotazioni {
        int id PK
        int user_id FK
        varchar tipo
        int campo_id FK
        int corso_id FK
        date data
        time slot_start
        varchar stato
        int quantity
    }

    abbonamenti {
        int id PK
        varchar nome
        numeric prezzo
        int durata_giorni
        text descrizione
    }

    abbonamenti_utenti {
        int id PK
        int user_id FK
        int abbonamento_id FK
        date data_inizio
        date data_fine
        boolean attivo
    }

    pagamenti {
        int id PK
        int user_id FK
        numeric totale
        timestamp data_pagamento
        varchar tipo
    }

    pagamenti_abbonamenti {
        int pagamento_id PK FK
        int abbonamento_utente_id FK
    }

    pagamenti_prenotazioni {
        int pagamento_id PK FK
        int prenotazione_id PK FK
    }

    %% Relazioni
    utenti ||--o{ prenotazioni : "fa"
    utenti ||--o{ abbonamenti_utenti : "ha"
    utenti ||--o{ pagamenti : "effettua"

    campi ||--o{ prenotazioni : "prenotato in"
    
    corsi ||--o{ orari_corsi : "ha orari"
    corsi ||--o{ prenotazioni : "prenotato come"

    prenotazioni ||--o{ pagamenti_prenotazioni : "pagato in"

    abbonamenti ||--o{ abbonamenti_utenti : "assegnato a"

    abbonamenti_utenti ||--o{ pagamenti_abbonamenti : "pagato con"

    pagamenti ||--o{ pagamenti_abbonamenti : "per abbonamento"
    pagamenti ||--o{ pagamenti_prenotazioni : "per prenotazione"
```

