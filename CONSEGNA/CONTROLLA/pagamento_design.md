# Modulo Pagamento - Backend

## Panoramica

Questo modulo gestisce i processi di pagamento per prenotazioni, corsi e abbonamenti, simulando un sistema di pagamento esterno.

## Diagramma delle Classi

```mermaid
classDiagram
    class PaymentController {
        -service: PaymentService
        -authMiddleware: HttpSecurity
        #registerCommands()
    }

    class ProcessPaymentCommand {
        -service: PaymentService
        +execute(body, query)
    }

    class PaymentService {
        <<interface>>
        +processPayment(userId, cartData) array
        +getPaymentHistory(userId) array
    }

    class StandardPaymentService {
        -paymentRepository: PaymentRepository
        -bookingRepository: BookingRepository
        -externalPaymentGateway: ExternalPaymentGateway
        +processPayment(userId, cartData)
        +getPaymentHistory(userId)
    }

    class PaymentRepository {
        <<interface>>
        +create(userId, amount, description, status, paymentMethod) int
        +getByUserId(userId) array
        +getById(id) array
        +updateStatus(paymentId, status) bool
    }

    class PostgrePaymentRepository {
        -db: PDO
        +create(userId, amount, description, status, paymentMethod)
        +getByUserId(userId)
        +getById(id)
        +updateStatus(paymentId, status)
    }

    class ExternalPaymentGateway {
        <<interface>>
        +process(amount, cardData) array
    }

    class SimulatedPaymentGateway {
        +process(amount, cardData)
    }

    class Payment {
        +id: int
        +userId: int
        +amount: float
        +description: string
        +status: string (pending|completed|failed|refunded)
        +paymentMethod: string
        +createdAt: DateTime
        +transactionId: string
    }

    class CartData {
        +items: CartItem[]
        +total: float
    }

    class CartItem {
        +type: string (booking|course|subscription)
        +id: int
        +name: string
        +price: float
        +date: string
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

    PaymentController --> ProcessPaymentCommand : crea
    ProcessPaymentCommand --> PaymentService
    StandardPaymentService ..|> PaymentService
    StandardPaymentService --> PaymentRepository
    StandardPaymentService --> ExternalPaymentGateway
    StandardPaymentService --> BookingRepository
    PostgrePaymentRepository ..|> PaymentRepository
    SimulatedPaymentGateway ..|> ExternalPaymentGateway
    PaymentController --> HttpSecurity
    AuthMiddleware ..|> HttpSecurity
```

## Flusso di Processamento Pagamento

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as PaymentController
    participant Command as ProcessPaymentCommand
    participant Service as StandardPaymentService
    participant Gateway as SimulatedPaymentGateway
    participant PaymentRepo as PostgrePaymentRepository
    participant BookingRepo as BookingRepository
    participant DB as PostgreSQL

    Client ->> Router: POST /payment/process {cart: {items, total}, paymentMethod, cardData}
    Router ->> Controller: resolveAction("process")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai userId dal token
    
    Command ->> Service: processPayment(userId, cartData)
    
    Note over Service: Crea pagamento con stato pending
    
    Service ->> PaymentRepo: create(userId, amount, description, "pending", paymentMethod)
    PaymentRepo ->> DB: INSERT payment
    DB -->> PaymentRepo: payment id
    PaymentRepo -->> Service: {payment_id, status: "pending"}
    
    Service ->> Gateway: process(amount, cardData)
    Gateway -->> Service: {success, transactionId}
    
    alt Pagamento riuscito
        Service ->> PaymentRepo: updateStatus(paymentId, "completed")
        PaymentRepo ->> DB: UPDATE payment SET status = 'completed'
        DB -->> PaymentRepo: success
        PaymentRepo -->> Service: true
        
        Note over Service: Conferma prenotazioni nel carrello
        
        loop Per ogni item nel carrello
            alt type == booking
                Service ->> BookingRepo: confirmBooking(userId, bookingId)
                BookingRepo ->> DB: UPDATE booking SET status = 'confirmed'
                DB -->> BookingRepo: success
                BookingRepo -->> Service: confirmed
            end
        end
        
        Service -->> Command: {payment_id, status: "completed", transaction_id}
        Command -->> Controller: Response(200, success, data)
        Controller -->> Router: Response
        Router -->> Client: HTTP 200 {payment_id, status, transaction_id}
    else Pagamento fallito
        Service ->> PaymentRepo: updateStatus(paymentId, "failed")
        PaymentRepo ->> DB: UPDATE payment SET status = 'failed'
        DB -->> PaymentRepo: success
        PaymentRepo -->> Service: true
        
        Note over Service: Rimuovi prenotazioni temporanee
        
        Service -->> Command: Error("Pagamento fallito")
        Command -->> Controller: Response(400, error, message)
        Controller -->> Router: Response
        Router -->> Client: HTTP 400 {error, payment_id}
    end
```

## Flusso di Recupero Storico Pagamenti

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as PaymentController
    participant Command as ProcessPaymentCommand
    participant Service as StandardPaymentService
    participant PaymentRepo as PostgrePaymentRepository
    participant DB as PostgreSQL

    Client ->> Router: GET /payment/history
    Router ->> Controller: resolveAction("history")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai userId dal token
    
    Command ->> Service: getPaymentHistory(userId)
    
    Service ->> PaymentRepo: getByUserId(userId)
    PaymentRepo ->> DB: query
    DB -->> PaymentRepo: payments rows
    PaymentRepo -->> Service: [{id, amount, description, status, paymentMethod, createdAt, transactionId}, ...]
    
    Service -->> Command: payments array
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {payments}
```
