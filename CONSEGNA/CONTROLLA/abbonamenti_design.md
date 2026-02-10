# Modulo Abbonamenti - Backend

## Panoramica

Questo modulo gestisce gli abbonamenti, permettendo agli utenti di visualizzare gli abbonamenti disponibili e acquistarli, e agli admin di gestire le tariffe e gli abbonamenti.

## Diagramma delle Classi

```mermaid
classDiagram
    class SubscriptionController {
        -service: SubscriptionService
        -authMiddleware: HttpSecurity
        #registerCommands()
    }

    class GetAllSubscriptionsCommand {
        -service: SubscriptionService
        +execute(body, query)
    }

    class PurchaseSubscriptionCommand {
        -service: SubscriptionService
        +execute(body, query)
    }

    class SubscriptionService {
        <<interface>>
        +getAllSubscriptions() array
        +purchaseSubscription(userId, subscriptionId) array
    }

    class StandardSubscriptionService {
        -subscriptionRepository: SubscriptionRepository
        -userRepository: AuthRepository
        -paymentService: PaymentService
        +getAllSubscriptions()
        +purchaseSubscription(userId, subscriptionId)
    }

    class SubscriptionRepository {
        <<interface>>
        +getAll() array
        +getById(id) array
        +getActiveSubscriptionByUserId(userId) array
        +create(userId, subscriptionId, startDate, endDate, isActive) int
    }

    class PostgreSubscriptionRepository {
        -db: PDO
        +getAll()
        +getById(id)
        +getActiveSubscriptionByUserId(userId)
        +create(userId, subscriptionId, startDate, endDate, isActive)
    }

    class Subscription {
        +id: int
        +name: string
        +duration: int (giorni)
        +price: float
        +maxAccesses: int
    }

    class UserSubscription {
        +id: int
        +userId: int
        +subscriptionId: int
        +startDate: DateTime
        +endDate: DateTime
        +isActive: boolean
    }

    class PaymentService {
        <<interface>>
        +processPayment(userId, amount, description) array
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

    SubscriptionController --> GetAllSubscriptionsCommand : crea
    SubscriptionController --> PurchaseSubscriptionCommand : crea
    GetAllSubscriptionsCommand --> SubscriptionService
    PurchaseSubscriptionCommand --> SubscriptionService
    StandardSubscriptionService ..|> SubscriptionService
    StandardSubscriptionService --> SubscriptionRepository
    StandardSubscriptionService --> AuthRepository
    StandardSubscriptionService --> PaymentService
    PostgreSubscriptionRepository ..|> SubscriptionRepository
    SubscriptionController --> HttpSecurity
    AuthMiddleware ..|> HttpSecurity
```

## Flusso di Visualizzazione Abbonamenti

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as SubscriptionController
    participant Command as GetAllSubscriptionsCommand
    participant Service as StandardSubscriptionService
    participant Repo as PostgreSubscriptionRepository
    participant DB as PostgreSQL

    Client ->> Router: GET /subscription
    Router ->> Controller: resolveAction("")
    Controller ->> Command: execute(body, query)

    Command ->> Service: getAllSubscriptions()
    Service ->> Repo: getAll()
    Repo ->> DB: query
    DB -->> Repo: subscriptions rows
    Repo -->> Service: [{id, name, duration, price, maxAccesses}, ...]

    Service -->> Command: subscriptions array
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {subscriptions}
```

## Flusso di Acquisto Abbonamento

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as SubscriptionController
    participant Command as PurchaseSubscriptionCommand
    participant Service as StandardSubscriptionService
    participant SubRepo as PostgreSubscriptionRepository
    participant UserRepo as PostgreAuthRepository
    participant Payment as PaymentService
    participant DB as PostgreSQL

    Client ->> Router: POST /subscription/purchase {subscriptionId}
    Router ->> Controller: resolveAction("purchase")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai userId dal token
    
    Command ->> Service: purchaseSubscription(userId, subscriptionId)
    
    Note over Service: Verifica utente non ha abbonamento attivo
    
    Service ->> SubRepo: getActiveSubscriptionByUserId(userId)
    SubRepo ->> DB: query
    DB -->> SubRepo: active subscription or null
    SubRepo -->> Service: subscription or null
    
    alt Abbonamento attivo esistente
        Service -->> Command: Error("Hai già un abbonamento attivo")
        Command -->> Controller: Response(400, error, message)
        Controller -->> Router: Response
        Router -->> Client: HTTP 400 {error}
    else Nessun abbonamento attivo
        Service ->> SubRepo: getById(subscriptionId)
        SubRepo ->> DB: query
        DB -->> SubRepo: subscription data
        SubRepo -->> Service: {id, name, price, duration}
        
        Service ->> Payment: processPayment(userId, price, "Abbonamento")
        Payment -->> Service: {success, paymentId}
        
        alt Pagamento riuscito
            Service ->> SubRepo: create(userId, subscriptionId, startDate, endDate, true)
            SubRepo ->> DB: query
            DB -->> SubRepo: new subscription id
            SubRepo -->> Service: {user_subscription_id}
            
            Service -->> Command: {user_subscription_id, endDate}
            Command -->> Controller: Response(201, success, data)
            Controller -->> Router: Response
            Router -->> Client: HTTP 201 {user_subscription_id, endDate}
        else Pagamento fallito
            Service -->> Command: Error("Pagamento fallito")
            Command -->> Controller: Response(400, error, message)
            Controller -->> Router: Response
            Router -->> Client: HTTP 400 {error}
        end
    end
```
