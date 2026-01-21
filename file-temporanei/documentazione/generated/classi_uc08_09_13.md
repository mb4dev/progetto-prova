# Classi UC08, UC09, UC13 - Modulo Finance (Pagamenti e Abbonamenti)

Questo modulo raggruppa le funzioni legate agli aspetti economici: pagamenti singoli (UC08), gestione e acquisto abbonamenti (UC09) e definizione delle politiche di prezzo/tariffari (UC13).

## Backend - Repository Layer

Gestione delle transazioni, delle definizioni dei prodotti (abbonamenti/tariffe) e delle sottoscrizioni utente.

```mermaid
classDiagram

class Repository {
    <<interface>>
}

class TransactionRepository {
    +create(tx: Transaction)
    +findByUser(userId: string) Transaction[]
}

class SubscriptionRepository {
    %% Abbonamenti attivi degli utenti
    +create(sub: UserSubscription)
    +findByUser(userId: string) UserSubscription
    +updateStatus(id: string, status: string)
}

class ProductRepository {
    %% Tariffe e Tipi di Abbonamento definiti dall'Admin (UC13)
    +findAll() Product[]
    +save(product: Product)
    +delete(id: string)
}

class Product {
    <<abstract>>
    +id: string
    +name: string
    +price: float
    +active: boolean
}

class Tariff {
    %% Prezzo orario campo o lezione
    +resourceType: string
    +timeSlots: TimeRange[]
}

class SubscriptionPlan {
    %% Pacchetto ingressi o temporale
    +durationDays: int
    +maxEntries: int
}

Product <|-- Tariff
Product <|-- SubscriptionPlan

ProductRepository ..> Product
TransactionRepository --|> Repository
SubscriptionRepository --|> Repository
ProductRepository --|> Repository
```

## Backend - Service Layer

Il `FinanceService` gestisce i pagamenti e il `ProductManagementService` permette all'admin di configurare i prezzi.

```mermaid 
classDiagram

class PaymentGateway {
    <<interface>>
    +charge(token: string, amount: float) PaymentResult
}

class FinanceService {
    -txRepo: TransactionRepository
    -subRepo: SubscriptionRepository
    -gateway: PaymentGateway
    +processPayment(userId: string, item: Product, paymentDetails: Object) Response
    +activateSubscription(userId: string, planId: string) Response
}

class ProductManagementService {
    %% Gestione listini (Admin UC13)
    -productRepo: ProductRepository
    +updateTariff(tariffId: string, newPrice: float)
    +createSubscriptionPlan(plan: SubscriptionPlan)
}

FinanceService --> PaymentGateway
FinanceService --> TransactionRepository
FinanceService --> SubscriptionRepository
ProductManagementService --> ProductRepository
```

## Backend - Controller Layer

```mermaid
classDiagram

class PaymentController {
    -financeService: FinanceService
    +checkout(req: Request)
    +webhook(req: Request)
}

class AdminFinanceController {
    %% UC13
    -productService: ProductManagementService
    +listProducts(req: Request)
    +saveProduct(req: Request)
}

PaymentController --> FinanceService
AdminFinanceController --> ProductManagementService
```

## Frontend - View e Presenter

Gestione del flow di pagamento e pannello admin.

```mermaid 
classDiagram

    class View {
        <<interface>>
    }

    class PaymentView {
        %% UI per inserimento dati carta o selezione metodo
        +renderCheckout(item: Object)
        +bindSubmit(callback: Function)
        +showProcessing()
        +showResult(success: bool)
    }

    class SubscriptionShopView {
        %% Vetrina abbonamenti (UC09)
        +renderPlans(plans: Array)
        +bindSelectPlan(callback: Function)
    }

    class AdminPricingView {
        %% Gestione tariffe (UC13)
        +renderProductList(products: Array)
        +renderEditor(product: Product)
        +bindSave(callback: Function)
    }

    class FinancePresenter {
        -payView: PaymentView
        -shopView: SubscriptionShopView
        -api: APIService
        +initShop()
        +initCheckout(itemId: string)
        +processPayment(data: Object)
    }

    class AdminFinancePresenter {
        -view: AdminPricingView
        -api: APIService
        +loadProducts()
        +saveProduct(data: Object)
    }

    PaymentView ..|> View
    SubscriptionShopView ..|> View
    AdminPricingView ..|> View
    
    FinancePresenter --> PaymentView
    FinancePresenter --> SubscriptionShopView
    AdminFinancePresenter --> AdminPricingView
```

---




### UC13 - Gestione Tariffe (Admin)

```mermaid
flowchart TD
    Start([Inizio]) --> AdminLogin[Login Admin]
    AdminLogin --> ListP[Visualizza Elenco Prodotti/Tariffe]
    ListP --> Action{Azione?}
    
    Action -->|Modifica| EditForm[Apre form modifica]
    Action -->|Nuovo| NewForm[Apre form creazione]
    
    EditForm --> AdminInput[Inserisce nuovi prezzi/dati]
    NewForm --> AdminInput
    
    AdminInput --> Save[Salva]
    Save --> Validate{Dati validi?}
    
    Validate -->|No| ShowErr[Mostra errore validazione]
    ShowErr --> AdminInput
    
    Validate -->|Sì| UpdateDB[Aggiorna DB]
    UpdateDB --> Reload[Ricarica elenco]
    Reload --> ListP
```

---

## Diagrammi di Sequenza

### UC09 - Acquisto Abbonamento

```mermaid
sequenceDiagram
    autonumber
    
    actor User
    participant View as SubscriptionShopView
    participant PayView as PaymentView
    participant Pres as FinancePresenter
    participant API as APIService
    participant Ctrl as PaymentController
    participant Svc as FinanceService
    participant Gate as PaymentGateway
    participant SubRepo as SubscriptionRepository
    
    User->>View: Seleziona Abbonamento (PlanID)
    View->>Pres: onSelectPlan(plan)
    Pres->>PayView: renderCheckout(plan)
    View-->>User: Mostra form pagamento
    
    User->>PayView: Inserisce dati carta e conferma
    PayView->>Pres: processPayment(cardData)
    Pres->>API: POST /api/payment/checkout
    API->>Ctrl: checkout(req)
    Ctrl->>Svc: processPayment(user, plan, cardData)
    
    Svc->>+Gate: charge(cardData, amount)
    Gate-->>-Svc: Success(txId)
    
    alt Pagamento OK
        Svc->>SubRepo: create(UserSubscription)
        Svc->>Svc: Log Transaction
        Svc-->>Ctrl: {success: true, subId: ...}
        Ctrl-->>API: 200 OK
        API-->>Pres: {success: true}
        Pres->>PayView: showResult(true)
    else Errore Pagamento
        Svc-->>Ctrl: {success: false, error: "Declined"}
        Ctrl-->>API: 400 Bad Request
        Pres->>PayView: showResult(false, "Declined")
    end
```

### UC13 - Aggiornamento Tariffa (Admin)

```mermaid
sequenceDiagram
    autonumber
    
    actor Admin
    participant View as AdminPricingView
    participant Pres as AdminFinancePresenter
    participant API as APIService
    participant Ctrl as AdminFinanceController
    participant Svc as ProductManagementService
    participant Repo as ProductRepository
    
    Admin->>View: Modifica prezzo Campo A
    View->>Pres: saveProduct(productData)
    Pres->>API: PUT /api/admin/products/{id}
    API->>Ctrl: saveProduct(req)
    Ctrl->>Svc: updateTariff(id, price)
    
    Svc->>Repo: save(product)
    Repo-->>Svc: success
    Svc-->>Ctrl: success
    Ctrl-->>API: 200 OK
    API-->>Pres: success
    Pres->>View: showSuccess("Prezzo aggiornato")
    Pres->>Pres: loadProducts()
    Pres->>View: renderProductList(...)
```
