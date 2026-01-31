## Endpoint

| Metodo | Path            | Descrizione                  | Request Body                           | Autenticazione | Successo      | Errori Principali                  |
|--------|-----------------|------------------------------|----------------------------------------|----------------|---------------|------------------------------------|
| POST   | `/auth/login`   | Login utente                 | `{"email": "...", "password": "..."}`  | No             | 200 OK        | 400, 401                           |
| POST   | `/auth/register`| Registrazione nuovo utente   | `{"name": "...", "email": "...", "password": "...", "role": "..."}` | No | 201 Created   | 400, 409                           |
| GET    | `/resource?type=field` | Ottiene i campi sportivi | | Sì | 200 OK | 400 |
| GET    | `/resource?type=course` | Ottiene i corsi | | Sì | 200 OK | 400 |
| POST   | `/booking/field` | Prenotazione campo | `{"resource_id": ..., "date": "...", "slot": "..."}` | Sì | 201 Created | 400, 401, 409 |
| GET    | `/subscriptions` | Ottiene abbonamenti disponibili | | Sì | 200 OK | 401 |
| POST   | `/bookings/occupied-slots` | Ottiene slot occupati | `{"resource_id": ..., "resource_type": "...", "start_date": "..."}` | Sì | 200 OK | 400, 401 |
| GET    | `/users/profile` | Ottiene profilo utente | | Sì | 200 OK | 401, 404 |
| PUT    | `/users/profile` | Aggiorna profilo utente | `{"name": "...", "email": "...", "password": "..."}` | Sì | 200 OK | 400, 401, 409 |
| GET    | `/bookings/history` | Ottiene storico prenotazioni | | Sì | 200 OK | 401 |
| DELETE | `/bookings/{bookingId}` | Cancella prenotazione | | Sì | 200 OK | 400, 401, 404 |
| POST   | `/payments/single` | Paga prenotazione singola | `{"items": [...], "total": ...}` | Sì | 200 OK | 400, 401 |
| POST   | `/payments/subscription` | Paga abbonamento | `{"subscriptionId": ..., "price": ...}` | Sì | 200 OK | 400, 401 |

### Esempi di Risposte JSON

#### Formato Standard errore

```json
{
    "code": 400,
    "success": false,
    "error": "Descrizione dell'errore"
}
```

#### POST `/auth/login`

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "user": {
            "id": 1,
            "name": "nome utente",
            "email": "email utente",
            "role": "USER"
        }
    }
}
```

---

#### POST `/auth/register`

**Successo (201 Created)**
```json
{
    "code": 201,
    "success": true,
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "user": {
            "id": 2,
            "name": "nome utente",
            "email": "email utente",
            "role": "USER"
        }
    }
}
```

---

#### GET `/resource?type=field`

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": [
        {
            "id": 1,
            "sport": "calcio",
            "price": 50.00
        }
    ]
}
```

---

#### GET `/resource?type=course`

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Corso Padel",
            "description": "Introduzione al padel",
            "price": 90.00,
            "capacity": 18,
            "schedule": ["09:00", "11:00", "15:00"]
        }
    ]
}
```

---

#### POST `/booking/field`

**Request Body**:
```json
{
    "resource_id": 1,
    "date": "2026-02-01",
    "slot": ["08:00", "08:30"]
}
```

**Successo (201 Created)**
```json
{
    "code": 201,
    "success": true,
    "data": {
        "booking_id": 1,
        "resource_id": 1,
        "date": "2026-02-01",
        "slot": ["08:00", "08:30"],
        "status": "confirmed"
    }
}
```

---

#### GET `/subscriptions`

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Mensile",
            "description": "Accesso illimitato per 30 giorni",
            "duration": 30,
            "price": 49.90
        },
        {
            "id": 2,
            "name": "Trimestrale",
            "description": "Accesso illimitato per 3 mesi",
            "duration": 90,
            "price": 129.90
        },
        {
            "id": 3,
            "name": "Annuale",
            "description": "Accesso illimitato per 12 mesi",
            "duration": 365,
            "price": 399.90
        }
    ]
}
```

---

#### POST `/bookings/occupied-slots`

**Request Body**:
```json
{
    "resource_id": 1,
    "resource_type": "field",
    "start_date": "2026-01-01"
}
```

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": {
        "2026-01-01": ["08:00", "08:30", "09:00"],
        "2026-01-02": ["14:00", "15:30"],
        "2026-01-03": ["10:00", "11:00", "12:00"]
    }
}
```

---

#### GET `/users/profile`

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": {
        "id": 1,
        "name": "Mario Rossi",
        "email": "mario.rossi@example.com",
        "role": "USER",
        "createdAt": "2026-01-15T10:30:00Z",
        "updatedAt": "2026-01-15T10:30:00Z"
    }
}
```

---

#### PUT `/users/profile`

**Request Body**:
```json
{
    "name": "Mario Rossi Aggiornato",
    "email": "nuovo.email@example.com",
    "password": "NuovaPassword123!",
    "currentPassword": "VecchiaPassword123!"
}
```

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": {
        "id": 1,
        "name": "Mario Rossi Aggiornato",
        "email": "nuovo.email@example.com",
        "role": "USER",
        "updatedAt": "2026-01-16T14:45:00Z"
    }
}
```

---

#### GET `/bookings/history`

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": [
        {
            "id": 1,
            "type": "field",
            "title": "Tennis",
            "date": "2026-02-01",
            "slot": ["08:00", "08:30"],
            "status": "confirmed",
            "amount": 70.00
        },
        {
            "id": 2,
            "type": "course",
            "title": "Palestra",
            "date": "2026-01-15",
            "slot": ["15:00"],
            "status": "completed",
            "amount": 70.00
        },
        {
            "id": 3,
            "type": "subscription",
            "title": "Abbonamento Mensile",
            "date": "2026-01-12",
            "slot": [],
            "status": "active",
            "amount": 49.90
        }
    ]
}
```

---

#### DELETE `/bookings/{bookingId}`

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": {
        "booking_id": 1,
        "status": "cancelled"
    }
}
```

**Errore (400 Bad Request) - Meno di 24 ore**
```json
{
    "code": 400,
    "success": false,
    "error": "Cancellazione non consentita: meno di 24 ore all'evento"
}
```

---

#### POST `/payments/single`

**Request Body**:
```json
{
    "items": [
        {
            "id": 1,
            "type": "field",
            "date": "2026-02-01",
            "slot": ["08:00", "08:30"],
            "price": 70.00
        }
    ],
    "total": 70.00
}
```

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": {
        "type": "single",
        "transaction_id": "tx_123456",
        "total": 70.00,
        "payment_id": 1,
        "status": "completed"
    }
}
```

---

#### POST `/payments/subscription`

**Request Body**:
```json
{
    "subscriptionId": 1,
    "price": 49.90
}
```

**Successo (200 OK)**
```json
{
    "code": 200,
    "success": true,
    "data": {
        "type": "subscription",
        "subscription_id": 4,
        "subscription_name": "Mensile",
        "transaction_id": "tx_789012",
        "total": 49.90,
        "expires_at": "2026-02-28",
        "payment_id": 2,
        "status": "completed"
    }
}
```

---

## Note sull'Autenticazione

Tutti gli endpoint contrassegnati come richiedono autenticazione necessitano:
- Header: `Authorization: Bearer <token>`
- Il token viene ottenuto tramite `/auth/login` o `/auth/register`
- Il middleware `AuthMiddleware` valida il token prima di elaborare la richiesta

## Codici di Stato HTTP Utilizzati

- **200 OK**: Richiesta completata con successo
- **201 Created**: Risorsa creata con successo
- **400 Bad Request**: Richiesta malformata o dati non validi
- **401 Unauthorized**: Token mancante o non valido
- **404 Not Found**: Risorsa non trovata
- **409 Conflict**: Violazione di vincoli (es. email già esistente)
