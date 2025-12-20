# Architettura Base

Questo documento descrive l'architettura generale del sistema. Le interfacce e classi astratte sono documentate in file separati nella directory `architettura_base/`.

## Interfacce e Classi Astratte

Per maggiori dettagli su ciascuna interfaccia o classe astratta, consultare i file dedicati:

### Backend
- [Router](architettura_base/Router.md) - Classe astratta per il routing delle richieste HTTP
- [URLParser](architettura_base/URLParser.md) - Interfaccia per il parsing delle URL
- [ResponseStrategy](architettura_base/ResponseStrategy.md) - Interfaccia per l'invio delle risposte (Strategy Pattern)
- [Controller](architettura_base/Controller.md) - Classe astratta per i controller
- [Repository](architettura_base/Repository.md) - Classe astratta per l'accesso ai dati
- [FieldRepository](architettura_base/FieldRepository.md) - Classe astratta per i repository dei campi
- [BookingRepository](architettura_base/BookingRepository.md) - Classe astratta per i repository delle prenotazioni
- [FieldService](architettura_base/FieldService.md) - Interfaccia per i servizi dei campi
- [BookingService](architettura_base/BookingService.md) - Interfaccia per i servizi delle prenotazioni

### Frontend
- [View](architettura_base/View.md) - Interfaccia per le view (MVP Pattern)
- [Presenter](architettura_base/Presenter.md) - Classe astratta per i presenter (MVP Pattern)
- [Observer](architettura_base/Observer.md) - Interfaccia per il pattern Observer
- [APIService](architettura_base/APIService.md) - Interfaccia per le chiamate API
- [SubView](architettura_base/SubView.md) - Interfaccia per le sub-view
- [SubPresenter](architettura_base/SubPresenter.md) - Interfaccia per i sub-presenter

---

I seguenti diagrammi mostrano come viene gestita una chiamata HTTP dal backend. 
Per la creazione del controller associato alla chiamata viene utilizzata una factory.
Per motivi di debug, per l'invio della risposta al client viene utilizzato il design pattern "Strategy".

## Backend - Flusso Request/Response

### Diagramma di Sequenza

```mermaid 
sequenceDiagram
    autonumber

    participant Client
    participant Router
    participant URLParser
    participant ControllerFactory
    participant Controller
    participant ResponseStrategy

    Client ->>+ Router: HTTP Request
    Router ->>+ URLParser: parse()
    URLParser -->>- Router: ParsedURL

    Router ->>+ ControllerFactory: create(controllerType)
    ControllerFactory -->>- Router: Controller

    Router ->>+ Controller: resolveAction(action)
    Controller -->>- Router: Response

    Router ->>+ ResponseStrategy: response(Response)
    ResponseStrategy -->>- Client: HTTP Response
```

### Diagramma delle Classi

```mermaid 
classDiagram

class Router {
    <<abstract>>
    #urlParser: URLParser
    #controllerFactory: ControllerFactory
    #responseStrategy: ResponseStrategy
    +dispatch() 
    #sendResponse(response: Response) 
}

class DefaultRouter {
    +dispatch() 
}

class URLParser {
    <<URLParser>>
}

class ResponseStrategy {
    <<ResponseStrategy>>
}

class ControllerFactory {
    -dbConnection: PDO
    +create(type: string) Controller
}

Router <|-- DefaultRouter
Router --> URLParser
Router --> ControllerFactory
Router --> ResponseStrategy
ControllerFactory --> PDO
ControllerFactory --> Controller
```

---

## Frontend - Pattern MVP con Observer

Il frontend implementa il pattern MVP (Model-View-Presenter) con Observer per la gestione degli eventi.

```mermaid 
classDiagram
    class View {
        <<View>>
    }
    
    class Presenter {
        <<Presenter>>
    }
    
    class APIService {
        <<APIService>>
    }
    
    class Observer {
        <<Observer>>
    }
    
    class DefaultObserver {
        -listeners: Map
        +subscribe(event: string, callback: Function)
        +notify(event: string, data: Object)
    }
    
    class MockAPIService {
        +login(email: string, password: string) Promise
        +register(email: string, name: string, password: string) Promise
    }
    
    DefaultObserver ..|> Observer
    MockAPIService ..|> APIService
    Presenter --> View
    Presenter --> APIService
   
    class AuthPresenter {
        +init()
        +update()
        #handleViewEvents()
    }
    
    class LoginView {
        +display(data)
        +template() string
        #bindEvents()
    }
    
    class RegisterView {
        +display(data) 
        +template() string
        #bindEvents() 
    }
    
    AuthPresenter --|> Presenter
    LoginView ..|> View
    RegisterView ..|> View
    
    AuthPresenter --> LoginView
    AuthPresenter --> RegisterView
    
    View ..> DefaultObserver : notify
    Presenter ..> DefaultObserver : subscribe
```

---

## Gestione Navigazione (MainView & MainPresenter)

Il sistema di navigazione principale è gestito dal `MainPresenter`, che agisce come orchestratore per le diverse sezioni dell'applicazione.

### Diagramma delle Classi

```mermaid
classDiagram
    direction LR

    class MainView {
        -mainContent: HTMLElement
        -activeRoute: string
        +display(data: Object) 
        -bindEvents() 
        -updateActiveTab()
    }

    class MainPresenter {
        -registry: Object
        +init() 
        -handleViewEvents() 
    }

    class SubPresenter {
        <<SubPresenter>>
    }

    class SubView {
        <<SubView>>
    }

    MainView ..|> View
    MainPresenter --|> Presenter
    MainPresenter --> MainView
    MainPresenter ..> SubPresenter : creates
    MainPresenter ..> SubView : creates
    MainView ..> DefaultObserver : notify
    MainPresenter ..> DefaultObserver : subscribe
```

### Diagramma di Sequenza (Navigazione)

```mermaid
sequenceDiagram
    autonumber

    actor User as Utente
    participant SideMenu as MainView (SideMenu)
    participant Observer as Observer (eventBus)
    participant MainP as MainPresenter
    participant SubP as SubPresenter
    participant SubV as SubView
    participant MainContent as MainView (Content Area)

    User ->> SideMenu: Click Navigation Button
    SideMenu ->>+ Observer: notify(MAIN_SELECT_EVENT, {main: route})
    Observer ->>+ MainP: trigger callback
    
    Note over MainP: Look up route in registry
    
    MainP ->>+ SubV: new SubView()
    MainP ->>+ SubP: new SubPresenter(view, service)
    MainP ->> SubP: init()
    MainP ->>+ MainContent: display({view, route})
    
    MainContent ->> MainContent: updateActiveTab()
    MainContent -->>- MainContent: append SubView to DOM
    
    deactivate SubP
    deactivate SubV
    deactivate MainP
    deactivate Observer
```

---

## Model - Classi Comuni

I seguenti model rappresentano le entità principali del sistema e sono utilizzati in diversi casi d'uso.

```mermaid
classDiagram

class User {
    +id: int
    +name: string
    +email: string
    +password: string
    +role: string
    +createdAt: string
}

class Field {
    +id: int
    +name: string
    +sport: string
    +pricePerHour: float
    +openingTime: string
    +closingTime: string
}

class Slot {
    +startTime: string
    +endTime: string
    +available: bool
    +fieldId: int
    +date: string
}

class Booking {
    +id: int
    +fieldId: int
    +userId: int
    +startTime: string
    +endTime: string
    +date: string
    +status: string
    +amount: float
    +createdAt: string
}

class Payment {
    +id: int
    +bookingId: int
    +userId: int
    +amount: float
    +status: string
    +method: string
    +transactionId: string
    +createdAt: string
}

class Response {
    +code: int
    +success: bool
    +data: mixed
    +message: string
}

Booking --> Field : references
Booking --> User : references
Payment --> Booking : references
Payment --> User : references
```

---

## Classi Comuni UC03 e UC04

Le seguenti classi sono condivise tra UC03 (Visualizzazione disponibilità) e UC04 (Prenotazione campo).

### Backend - Repository Layer

```mermaid
classDiagram

class Repository {
    <<Repository>>
}

class FieldRepository {
    <<FieldRepository>>
}

class DefaultFieldRepository {
    +getAll() Field[]
    +getById(id: int) Field
    +getByType(sport: string) Field[]
}

class BookingRepository {
    <<BookingRepository>>
}

class DefaultBookingRepository {
    +create(booking: Booking) Booking
    +getById(id: int) Booking
    +checkAvailability(fieldId: int, startTime: string, endTime: string) bool
    +getOccupiedSlotsByWeek(fieldId: int, startDate: string, endDate: string) Slot[]
    +updateStatus(id: int, status: string) bool
}

class FieldNotFoundException {
    +message: string
}

class BookingConflictException {
    +message: string
}

class BookingNotFoundException {
    +message: string
}

FieldRepository --|> Repository
DefaultFieldRepository --|> FieldRepository
BookingRepository --|> Repository
DefaultBookingRepository --|> BookingRepository
Repository --> PDO : usa

DefaultFieldRepository ..> FieldNotFoundException : throws
DefaultBookingRepository ..> BookingConflictException : throws
DefaultBookingRepository ..> BookingNotFoundException : throws

FieldNotFoundException --|> Exception
BookingConflictException --|> Exception
BookingNotFoundException --|> Exception
```

### Backend - Service Layer

```mermaid
classDiagram

class FieldService {
    <<FieldService>>
}

class DefaultFieldService {
    -fieldRepository: FieldRepository
    -bookingRepository: BookingRepository
    +getFields() Response
    +getOccupiedSlots(fieldId: int, startDate: string, endDate: string) Response
}

class BookingService {
    <<BookingService>>
}

class DefaultBookingService {
    -bookingRepository: BookingRepository
    +createBooking(userId: int, fieldId: int, startTime: string, endTime: string) Response
    +confirmBooking(bookingId: int) Response
    +cancelBooking(bookingId: int) Response
    -validateBookingTime(startTime: string, endTime: string) bool
}

DefaultFieldService ..|> FieldService
DefaultFieldService --> FieldRepository
DefaultFieldService --> BookingRepository

DefaultBookingService ..|> BookingService
DefaultBookingService --> BookingRepository

FieldService --> Response
BookingService --> Response
```

### Backend - Controller Layer

```mermaid
classDiagram

class Controller {
    <<Controller>>
}

class FieldController {
    -fieldService: FieldService
    +resolveAction(action: string) Response
}

class BookingController {
    -bookingService: BookingService
    +resolveAction(action: string) Response
}

FieldController --|> Controller
BookingController --|> Controller
FieldController --> FieldService
BookingController --> BookingService
Controller --> Response
```

### Frontend - View e Presenter

```mermaid
classDiagram

class CampiView {
    -fields: Field[]
    -selectedField: Field
    -selectedDate: Date
    -occupiedSlots: Slot[]
    +display(data: Object) 
    +render() 
    -renderFieldList() 
    -renderCalendar() 
    -renderSlots() 
    -markOccupiedSlots() 
}

class CampiPresenter {
    +init() 
    +update() 
    #handleViewEvents() 
    -loadFields() 
    -loadOccupiedSlots(fieldId: int, startDate: string, endDate: string) 
}

class BookingView {
    -selectedSlot: Slot
    -bookingDetails: Object
    +display(data: Object) 
    +render() 
    -renderSlotConfirmation() 
    -renderConfirmation() 
}

class BookingPresenter {
    +init() 
    +update() 
    #handleViewEvents() 
    -createBooking(slotData: Object) 
    -confirmBooking(bookingId: int) 
}

CampiView ..|> View
BookingView ..|> View
CampiPresenter --|> Presenter
BookingPresenter --|> Presenter

CampiPresenter --> CampiView
BookingPresenter --> BookingView

CampiPresenter ..> Observer : subscribe
CampiView ..> Observer : notify
BookingPresenter ..> Observer : subscribe
BookingView ..> Observer : notify
```