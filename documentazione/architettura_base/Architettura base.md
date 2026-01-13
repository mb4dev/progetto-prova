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

### Frontend
- [View](architettura_base/View.md) - Interfaccia per le view (MVP Pattern)
- [Presenter](architettura_base/Presenter.md) - Classe astratta per i presenter (MVP Pattern)
- [Observer](architettura_base/Observer.md) - Interfaccia per il pattern Observer
- [APIService](architettura_base/APIService.md) - Interfaccia per le chiamate API

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
    MainPresenter ..> SubPresenter : crea
    MainPresenter ..> SubView : crea
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

    User ->> SideMenu: Click bottone navigazione
    SideMenu ->>+ Observer: notify(MAIN_SELECT_EVENT, {main: route})
    Observer ->>+ MainP: callback
    
    Note over MainP: Look up route in registry
    
    MainP ->>+ SubV: new SubView()
    MainP ->>+ SubP: new SubPresenter(view, service)
    MainP ->> SubP: init()
    MainP ->>+ MainContent: display()
    
    MainContent ->> MainContent: updateActiveTab()
    MainContent -->>- MainContent: append SubView al DOM
    
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

class Resource {
    <<abstract>>
    +id: int
    +type: string : "FIELD" o "COURSE"
    +name: string
    +price: double
}

class Field {
    +sport: string
}

class Course {
    +description: string
    +startDate: Date
    +endDate: Date
}

class Lesson {
    +id: int
    +courseId: int
    +date: Date
    +startTime: Time
    +endTime: Time
    +capacity: int
    +enrolledCount: int
}

Resource <|-- Field
Resource <|-- Course
Course *-- Lesson : contains

class Slot {
    +startTime: string
    +endTime: string
    +available: bool
    +fieldId: int
    +date: string
}

class Booking {
    <<abstract>>
    +id: int
    +userId: int
    +status: string
    +amount: float
    +createdAt: string
}

class FieldBooking {
    +fieldId: int
    +date: Date
    +startTime: Time
    +endTime: Time
}

class CourseBooking {
    +lessonId: int
}

Booking <|-- FieldBooking
Booking <|-- CourseBooking

Booking --> User : references
FieldBooking --> Field : references
CourseBooking --> Lesson : references
Payment --> Booking : references
Payment --> User : references

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
```

---
