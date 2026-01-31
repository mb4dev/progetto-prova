## Endpoint

| Metodo | Path            | Descrizione                  | Request Body                           | Successo      | Errori Principali                  |
|--------|-----------------|------------------------------|----------------------------------------|---------------|------------------------------------|
| POST   | `/auth/login`   | Login utente                 | `{"email": "...", "password": "..."}`  | 200 OK        | 400, 401                           |
| POST   | `/auth/register`| Registrazione nuovo utente   | `{"name": "...", "email": "...", "password": "...", "role": "..."}` | 201 Created   | 400, 409                           |
| POST   | `/booking/field` | Prenotazione campo | `{"user_id": ..., "field_id": ..., "date": "...", "slot": "..."}` | 201 Created | 400, 401, 409 |
| GET | `/resource?type=campo` | Ottiene i campi sportivi | | 200 OK | 400 |
| GET | `/resource?type=corso` | Ottiene i corsi | | 200 OK | 400 |

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

#### GET `/resource?type=campo`

**Successo (200 OK)**
```json
"code": 200,
"success": true,
"data" : [
    {
        "id": 1,
        "sport": "calcio",
        "price": 50.00
    }
]
```

---

#### POST `/booking/field`

**Successo (201 Created)**
```json

```

---

#### GET `/resource?type=corso`

**Successo (200 OK)**
```json
"code": 200,
"success": true,
"data" : [
	{
  		"id": 1,
  		"name": "Corso Padel",
  		"description": "Introduzione al padel",
  		"price": 90.00,
  		"capacity": 18,
  		"schedule": ["09:00", "11:00", "15:00"]
	}
]
```

---