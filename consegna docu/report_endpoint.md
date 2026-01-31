# Report Endpoint API

## Panoramica

Questo report analizza gli endpoint necessari per collegare il frontend (`@implementazione/frontend/`) con il backend (`@implementazione/backendV3/`).

---

## Endpoint Attualmente Implementati nel Backend

| Metodo | Path | Descrizione | Autenticazione |
|--------|------|-------------|----------------|
| POST | `/auth/login` | Login utente | No |
| POST | `/auth/register` | Registrazione nuovo utente | No |
| GET | `/resource?type=field` | Ottiene i campi sportivi | Sì |
| GET | `/resource?type=course` | Ottiene i corsi | Sì |
| POST | `/booking/field` | Prenotazione campo | Sì |

---

## Endpoint Richiesti dal Frontend

Il frontend utilizza le seguenti funzioni del `MockAPIService`:

### Auth (già implementati)
- `login(email, password)` → POST `/auth/login`
- `register(email, name, password)` → POST `/auth/register`

### Resources (parzialmente implementati)
- `getSports()` → GET `/resource?type=field`
- `getCourses()` → GET `/resource?type=course`
- `getSubscriptions()` → **NON IMPLEMENTATO**

### Prenotazioni
- `getOccupiedSlotsForWeek(startDateString)` → **NON IMPLEMENTATO**
- `getOccupiedSlotsForWeek(startDateString, resourceId, resourceType)` → **NON IMPLEMENTATO**

### Profilo
- `getProfile()` → **NON IMPLEMENTATO**
- `updateProfile(data)` → **NON IMPLEMENTATO**

### Storico
- `getHistory()` → **NON IMPLEMENTATO**

### Pagamenti
- `processSinglePayment(data)` → **NON IMPLEMENTATO**
- `processSubscriptionPayment(data)` → **NON IMPLEMENTATO**

---

## Endpoint da Implementare

### 1. GET `/subscriptions`

**Descrizione**: Ottiene la lista degli abbonamenti disponibili.

**Request**: Nessun parametro

**Response (200 OK)**:
```json
{
    "code": 200,
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Mensile",
            "description": "Accesso illimitato per 30 giorni",
            "price": 49.90
        },
        {
            "id": 2,
            "name": "Trimestrale",
            "description": "Accesso illimitato per 3 mesi",
            "price": 129.90
        },
        {
            "id": 3,
            "name": "Annuale",
            "description": "Accesso illimitato per 12 mesi",
            "price": 399.90
        }
    ]
}
```

---

### 2. POST `/bookings/occupied-slots`

**Descrizione**: Ottiene gli slot occupati per una risorsa in un range di date.

**Request Body**:
```json
{
    "resource_id": 1,
    "resource_type": "field" | "course",
    "start_date": "2026-01-01"
}
```

**Response (200 OK)**:
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

### 3. GET `/users/profile`

**Descrizione**: Ottiene il profilo dell'utente autenticato.

**Autenticazione**: Richiesta (JWT)

**Response (200 OK)**:
```json
{
    "code": 200,
    "success": true,
    "data": {
        "id": 1,
        "name": "Mario Rossi",
        "email": "mario.rossi@example.com",
        "role": "USER"
    }
}
```

---

### 4. PUT `/users/profile`

**Descrizione**: Aggiorna il profilo dell'utente autenticato.

**Autenticazione**: Richiesta (JWT)

**Request Body**:
```json
{
    "name": "Mario Rossi Aggiornato",
    "email": "nuovo.email@example.com"
}
```

**Response (200 OK)**:
```json
{
    "code": 200,
    "success": true,
    "data": {
        "id": 1,
        "name": "Mario Rossi Aggiornato",
        "email": "nuovo.email@example.com",
        "role": "USER"
    }
}
```

---

### 5. GET `/bookings/history`

**Descrizione**: Ottiene lo storico delle prenotazioni dell'utente.

**Autenticazione**: Richiesta (JWT)

**Response (200 OK)**:
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
            "amount": 70.00
        },
        {
            "id": 2,
            "type": "course",
            "title": "Palestra",
            "date": "2026-01-15",
            "slot": ["15:00"],
            "amount": 70.00
        },
        {
            "id": 3,
            "type": "subscription",
            "title": "Abbonamento Mensile",
            "date": "2026-01-12",
            "slot": [],
            "amount": 49.90
        }
    ]
}
```

---

### 6. POST `/payments/single`

**Descrizione**: Processa il pagamento per una prenotazione singola.

**Autenticazione**: Richiesta (JWT)

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

**Response (200 OK)**:
```json
{
    "code": 200,
    "success": true,
    "data": {
        "type": "single",
        "transactionId": "tx_123456",
        "total": 70.00
    }
}
```

---

### 7. POST `/payments/subscription`

**Descrizione**: Processa il pagamento per un abbonamento.

**Autenticazione**: Richiesta (JWT)

**Request Body**:
```json
{
    "subscriptionId": 1,
    "price": 49.90
}
```

**Response (200 OK)**:
```json
{
    "code": 200,
    "success": true,
    "data": {
        "type": "subscription",
        "subscriptionId": 1,
        "transactionId": "tx_789012",
        "expiresAt": "2026-02-28"
    }
}
```

---

## Riepilogo Endpoint Mancanti

| Endpoint | Metodo | Priorità |
|----------|--------|----------|
| `/subscriptions` | GET | Alta |
| `/bookings/occupied-slots` | POST | Alta |
| `/users/profile` | GET | Media |
| `/users/profile` | PUT | Media |
| `/bookings/history` | GET | Media |
| `/payments/single` | POST | Alta |
| `/payments/subscription` | POST | Alta |

---

## Note sull'Autenticazione

Tutti gli endpoint contrassegnati come "Richiesta" richiedono:
- Header: `Authorization: Bearer <token>`
- Il token viene ottenuto tramite `/auth/login` o `/auth/register`

---

## Considerazioni per l'Implementazione

1. **Slot Occupati**: L'implementazione attuale nel frontend usa mock. Il backend dovrà interfacciarsi con il database per calcolare gli slot già prenotati.

2. **Pagamenti**: Il backend dovrà gestire l'integrazione con un provider di pagamenti (es. Stripe, PayPal). Gli endpoint attuali sono placeholders.

3. **Storico**: Dovrà aggregare prenotazioni di campi, corsi e abbonamenti.

4. **Profilo**: L'endpoint PUT dovrà gestire la validazione dell'email univoca.
