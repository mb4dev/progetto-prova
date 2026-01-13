# Classi UC07, UC10 - Modulo Gestione Utente e Storico

Questo modulo gestisce l'interazione dell'utente con le proprie attività passate e future, permettendo la visualizzazione dello storico (UC10) e la gestione delle prenotazioni attive come la cancellazione (UC07).

## Backend - Repository Layer

Si estende il `BookingRepository` (già definito in UC03/06) per includere le query specifiche per utente e gli aggiornamenti di stato.

```mermaid
classDiagram

class Repository {
    <<interface>>
}

class BookingRepository {
    <<interface>>
    +findByUserId(userId: string) Booking[]
    +updateStatus(bookingId: string, status: BookingStatus) boolean
}

class BookingStatus {
    <<enumeration>>
    CONFIRMED
    CANCELLED
    COMPLETED
    REFUNDED
}

class Booking {
    +id: string
    +userId: string
    +resourceDetails: ResourceSnapshot
    +pricePaid: float
    +canCancel(): boolean
}

BookingRepository --|> Repository
BookingRepository ..> Booking
Booking ..> BookingStatus
```

## Backend - Service Layer

Il `UserDashboardService` centralizza la logica per il cruscotto utente.

```mermaid 
classDiagram

class BookingService {
    <<interface>>
    %% Metodi base definiti in altri moduli
}

class UserDashboardService {
    -bookingRepo: BookingRepository
    -paymentService: PaymentService
    +getUserHistory(userId: string) HistoryDTO
    +cancelBooking(bookingId: string, userId: string) Response
}

class PaymentService {
    <<interface>>
    +refundTransaction(transactionId: string) boolean
}

class HistoryDTO {
    +activeBookings: Booking[]
    +pastBookings: Booking[]
    +stats: Object
}

UserDashboardService --> BookingRepository
UserDashboardService --> PaymentService
UserDashboardService ..> HistoryDTO : produces
```

## Backend - Controller Layer

```mermaid
classDiagram

class BaseController {
    <<abstract>>
}

class DashboardController {
    -dashboardService: UserDashboardService
    +getHistory(req: Request)
    +cancelBooking(req: Request)
}

DashboardController --|> BaseController
DashboardController --> UserDashboardService
```

## Frontend - View e Presenter

Si utilizza un pattern **MVP** dove il Presenter recupera i dati storici e gestisce le azioni utente (es. click su "Cancella").

```mermaid 
classDiagram

    class View {
        <<interface>>
    }

    class HistoryView {
        -container: HTMLElement
        +render(history: HistoryDTO)
        +showError(msg: string)
        +showSuccess(msg: string)
        +bindCancelAction(callback: Function)
        +bindFilterChange(callback: Function)
    }

    class HistoryPresenter {
        -view: HistoryView
        -api: APIService
        +loadHistory()
        +handleCancel(bookingId: string)
        -filterHistory(criteria: Object)
    }

    class BookingCardComponent {
        %% Componente UI riutilizzabile per singola voce
        +render(booking: Booking)
        +onAction(callback: Function)
    }

    HistoryView ..|> View
    HistoryPresenter --> HistoryView
    HistoryView ..> BookingCardComponent : uses
```

---




### UC10 - Caricamento Storico


