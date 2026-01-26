# Diagramma ER del Database Centro Sportivo

```mermaid
erDiagram
    utenti {
        int id PK
        varchar name
        varchar email UK
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

    prenotazioni {
        int id PK
        int user_id FK
        varchar tipo
        int campo_id FK
        int corso_id FK
        date data
        time slot_start
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
    }

    pagamenti {
        int id PK
        int user_id FK
        numeric totale
        timestamp data_pagamento
        varchar tipo
    }

    pagamenti_abbonamenti {
        int pagamento_id PK,FK
        int abbonamento_utente_id FK
    }

    pagamenti_prenotazioni {
        int pagamento_id FK
        int prenotazione_id FK
    }

    utenti ||--o{ prenotazioni : "effettua"
    utenti ||--o{ abbonamenti_utenti : "acquista"
    utenti ||--o{ pagamenti : "paga"

    campi ||--o{ prenotazioni : "prenota"
    corsi ||--o{ prenotazioni : "prenota"

    abbonamenti ||--o{ abbonamenti_utenti : "sottoscrive"

    prenotazioni ||--o{ pagamenti_prenotazioni : "pagata da"
    abbonamenti_utenti ||--o{ pagamenti_abbonamenti : "pagata da"

    pagamenti ||--|| pagamenti_abbonamenti : "riferisce"
    pagamenti ||--o{ pagamenti_prenotazioni : "riferisce"
```

## Legenda
- **PK**: Primary Key
- **FK**: Foreign Key
- **UK**: Unique Key (Chiave Unica)
- **||--o{**: Relazione uno a molti
- **}o--||**: Relazione molti a uno
- **||--||**: Relazione uno a uno