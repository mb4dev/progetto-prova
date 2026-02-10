# Modulo History - Backend

## Panoramica

Questo modulo gestisce lo storico delle prenotazioni e pagamenti degli utenti, permettendo loro di visualizzare il loro storico e di cancellare prenotazioni future.

## Diagramma delle Classi

```mermaid
classDiagram
    class HistoryController {
        -service: HistoryService
        -authMiddleware: HttpSecurity
        #registerCommands()
    }

    class GetHistoryCommand {
        -service: HistoryService
        +execute(body, query)
    }

    class CancelBookingCommand {
        -service: HistoryService
        +execute(body, query)
    }

    class HistoryService {
        <<interface>>
        +getUserHistory(userId) array
        +cancelBooking(userId, bookingId) array
    }

    class StandardHistoryService {
        -bookingRepository: BookingRepository
        -paymentRepository: PaymentRepository
        -userRepository: AuthRepository
        +getUserHistory(userId)
        +cancelBooking(userId, bookingId)
    }

    class BookingRepository {
        <<interface>>
        +getByUserId(userId) array
        +getById(bookingId) array
        +cancel(bookingId) bool
        +isCancellable(bookingId) bool
    }

    class PostgreBookingRepository {
        -db: PDO
        +getByUserId(userId)
        +getById(bookingId)
        +cancel(bookingId)
        +isCancellable(bookingId)
    }

    class PaymentRepository {
        <<interface>>
        +getByUserId(userId) array
    }

    class PostgrePaymentRepository {
        -db: PDO
        +getByUserId(userId)
    }

    class HistoryItem {
        +id: int
        +type: string (booking|payment|subscription)
        +date: DateTime
        +title: string
        +status: string
        +amount: float
        +details: array
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

    HistoryController --> GetHistoryCommand : crea
    HistoryController --> CancelBookingCommand : crea
    GetHistoryCommand --> HistoryService
    CancelBookingCommand --> HistoryService
    StandardHistoryService ..|> HistoryService
    StandardHistoryService --> BookingRepository
    StandardHistoryService --> PaymentRepository
    StandardHistoryService --> AuthRepository
    PostgreBookingRepository ..|> BookingRepository
    PostgrePaymentRepository ..|> PaymentRepository
    HistoryController --> HttpSecurity
    AuthMiddleware ..|> HttpSecurity
```

## Flusso di Visualizzazione Storico

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as HistoryController
    participant Command as GetHistoryCommand
    participant Service as StandardHistoryService
    participant BookingRepo as PostgreBookingRepository
    participant PaymentRepo as PostgrePaymentRepository
    participant DB as PostgreSQL

    Client ->> Router: GET /history
    Router ->> Controller: resolveAction("")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai userId dal token
    
    Command ->> Service: getUserHistory(userId)
    
    Service ->> BookingRepo: getByUserId(userId)
    BookingRepo ->> DB: query
    DB -->> BookingRepo: bookings rows
    BookingRepo -->> Service: [{id, resourceId, date, slot, status, type}, ...]
    
    Service ->> PaymentRepo: getByUserId(userId)
    PaymentRepo ->> DB: query
    DB -->> PaymentRepo: payments rows
    PaymentRepo -->> Service: [{id, amount, date, status, description}, ...]
    
    Note over Service: Unisce e ordina cronologicamente
    
    Service -->> Command: [{id, type, date, title, status, amount, details}, ...]
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {history}
```

## Flusso di Cancellazione Prenotazione

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as HistoryController
    participant Command as CancelBookingCommand
    participant Service as StandardHistoryService
    participant Repo as PostgreBookingRepository
    participant DB as PostgreSQL

    Client ->> Router: DELETE /history/booking/{bookingId}
    Router ->> Controller: resolveAction("booking/{bookingId}")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai userId e bookingId
    
    Command ->> Service: cancelBooking(userId, bookingId)
    
    Service ->> Repo: getById(bookingId)
    Repo ->> DB: query
    DB -->> Repo: booking data
    Repo -->> Service: {id, userId, date, slot, status}
    
    alt Prenotazione non appartiene all'utente
        Service -->> Command: Error("Prenotazione non trovata")
        Command -->> Controller: Response(404, error, message)
        Controller -->> Router: Response
        Router -->> Client: HTTP 404 {error}
    else Prenotazione già cancellata
        Service -->> Command: Error("Prenotazione già cancellata")
        Command -->> Controller: Response(400, error, message)
        Controller -->> Router: Response
        Router -->> Client: HTTP 400 {error}
    else Verifica cancellabilità
        Service ->> Repo: isCancellable(bookingId)
        Repo ->> DB: query
        DB -->> Repo: isCancellable
        Repo -->> Service: true/false
        
        alt Non cancellabile (meno di 24 ore)
            Service -->> Command: Error("Cancellazione non consentita: meno di 24 ore all'evento")
            Command -->> Controller: Response(400, error, message)
            Controller -->> Router: Response
            Router -->> Client: HTTP 400 {error}
        else Cancellabile
            Service ->> Repo: cancel(bookingId)
            Repo ->> DB: UPDATE booking SET status = 'cancelled'
            DB -->> Repo: success
            Repo -->> Service: true
            
            Service -->> Command: {booking_id, status: "cancelled"}
            Command -->> Controller: Response(200, success, data)
            Controller -->> Router: Response
            Router -->> Client: HTTP 200 {booking_id, status}
        end
    end
```
