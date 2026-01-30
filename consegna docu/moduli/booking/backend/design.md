# Modulo prenotazione - Backend

## Panoramica

Questo modulo gestisce le prenotazioni dei campi sportivi. Utilizza il pattern Command per incapsulare le operazioni di prenotazione.

## Diagramma delle Classi

```mermaid
classDiagram
    class BookingController {
        -service: BookingService
        -authMiddleware: HttpSecurity
        #registerCommands()
    }

    class InsertFieldBookingCommand {
        -service: BookingService
        +execute(body, query)
    }

    class BookingService {
        <<interface>>
        +insertBooking(userId, resourceId, date, slot) array
    }

    class FieldsBookingService {
        -fieldsRepo: FieldsRepository
        -bookingRepo: BookingRepository
        +insertBooking(userId, resourceId, date, slot)
    }

    class BookingRepository {
        <<interface>>
        +getBooking(resourceId, date, slot)
        +insertBooking(userId, resourceId, date, slot) int
    }

    class FieldBookingRepository {
        -db: PDO
        +getBooking(resourceId, date, slot)
        +insertBooking(userId, resourceId, date, slot)
    }

    class FieldsRepository {
        <<interface>>
        +getAll() array
        +getResourceById(id) array
    }

    class PostgreFieldsRepository {
        -db: PDO
        +getAll()
        +getResourceById(id)
    }

    class HttpSecurity {
        <<interface>>
        +authenticate(token) User
    }

    class AuthMiddleware {
        -tokenService: TokenService
        -authRepository: AuthRepository
        +authenticate(token)
    }

    BookingController --> InsertFieldBookingCommand : crea
    InsertFieldBookingCommand --> BookingService
    FieldsBookingService ..|> BookingService
    FieldsBookingService --> FieldsRepository
    FieldsBookingService --> BookingRepository
    FieldBookingRepository ..|> BookingRepository
    PostgreFieldsRepository ..|> FieldsRepository
    BookingController --> HttpSecurity
    AuthMiddleware ..|> HttpSecurity
```

## Flusso di Prenotazione Campo (Diagramma di Sequenza)

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as BookingController
    participant Command as InsertFieldBookingCommand
    participant Service as FieldsBookingService
    participant FieldsRepo as PostgreFieldsRepository
    participant BookingRepo as FieldBookingRepository
    participant DB as PostgreSQL

    Client ->> Router: POST /booking/field {resourceId, date, slot}
    Router ->> Controller: resolveAction("field")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body)
    
    Command ->> Service: insertBooking(userId, resourceId, date, slot)
    
    Note over Service: Validazione campo esistente
    
    Service ->> FieldsRepo: getResourceById(resourceId)
    FieldsRepo ->> DB: SELECT * FROM centro_sportivo.campi WHERE id = ?
    DB -->> FieldsRepo: campo row
    FieldsRepo -->> Service: campo data
    
    Note over Service: Validazione data non passata
    
    Service ->> BookingRepo: insertBooking(userId, resourceId, date, slot)
    BookingRepo ->> DB: INSERT INTO centro_sportivo.prenotazioni...
    DB -->> BookingRepo: new booking id
    BookingRepo -->> Service: {booking_id}
    
    Service -->> Command: {booking_id}
    Command -->> Controller: Response(201, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 201 {booking_id}
```

## Endpoint

| Metodo | Path | Descrizione |
|--------|------|-------------|
| POST | `/booking/field` | Prenota un campo sportivo |

## Comandi

### InsertFieldBookingCommand

```php
class InsertFieldBookingCommand extends Command {
    public function __construct(private BookingService $service) {}
    
    public function execute(array $params, array $query = []): Response {
        $userId = $params['userId'];  // From JWT token
        $resourceId = (int)$params['resourceId'];
        $date = $params['date'];
        $slot = $params['slot'];
        
        $result = $this->service->insertBooking($userId, $resourceId, $date, $slot);
        return new Response(201, true, $result);
    }
    
    public function getRequiredBodyParameters(): array { 
        return ['resourceId', 'date', 'slot']; 
    }
    
    public function getRequiredQueryParameters(): array { return []; }
    public function getRequiredHttpMethod(): string { return 'post'; }
    public function requiresAuthentication(): bool { return true; }
    public function getRequiredRoles(): array { return ['user', 'admin']; }
}
```

## Dipendenze del Modulo

```mermaid
classDiagram
    class BookingController {
        <<depends on>>
    }
    class BookingService {
        <<depends on>>
    }
    class BookingRepository {
        <<depends on>>
    }
    class FieldsRepository {
        <<depends on>>
    }
    class HttpSecurity {
        <<depends on>>
    }
```

## Note

- Il modulo utilizza **PostgreSQL** come database
- Le prenotazioni sono salvate con stato iniziale `carrello`
- Ogni campo può essere prenotato solo se lo slot è libero
- La validazione impedisce prenotazioni per date passate
- Gli ID degli utenti vengono estratti dal token JWT