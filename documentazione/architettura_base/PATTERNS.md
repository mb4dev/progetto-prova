# Design Patterns Utilizzati nel Progetto

Questo documento descrive i design pattern implementati nell'architettura del sistema.

---

## Indice dei Pattern

1. [Model-View-Presenter (MVP)](#model-view-presenter-mvp)
2. [Observer Pattern](#observer-pattern)
3. [Repository Pattern](#repository-pattern)
4. [Strategy Pattern](#strategy-pattern)
5. [Factory Pattern](#factory-pattern)

---

## Model-View-Presenter (MVP)

### Scopo

Separare la logica di presentazione dalla logica di business e dalla visualizzazione, migliorando la testabilità e la manutenibilità del codice frontend.

### Componenti

```mermaid
classDiagram
class View {
    <<interface>>
    +display(data: Object)
    +template() string
    #bindEvents()
}

class Presenter {
    <<abstract>>
    #view: View
    #api: APIService
    +init()
    +update()
    #handleViewEvents()
}

class ConcreteView {
    +display(data: Object)
    +template() string
    #bindEvents()
}

class ConcretePresenter {
    +init()
    +update()
    #handleViewEvents()
}

ConcreteView ..|> View
ConcretePresenter --|> Presenter
ConcretePresenter --> View
ConcretePresenter --> APIService
```

## Observer Pattern

### Scopo

Permettere la comunicazione tra View e Presenter, disaccoppiando i componenti.

### Struttura

```mermaid
classDiagram
class Observer {
    <<interface>>
    +subscribe(event: string, callback: Function)
    +notify(event: string, data: Object)
}

class DefaultObserver {
    -listeners: Map
    +subscribe(event: string, callback: Function)
    +notify(event: string, data: Object)
}

class View {
    +notify(event, data)
}

class Presenter {
    +subscribe(event, callback)
}

DefaultObserver ..|> Observer
View ..> Observer : usa
Presenter ..> Observer : usa
```

### Flusso di Comunicazione

1. **View** notifica un evento tramite `notify()`
2. **Observer** propaga l'evento a tutti i subscriber
3. **Presenter** riceve l'evento tramite callback registrato con `subscribe()`

---

## Repository Pattern

### Scopo

Astrarre l'accesso ai dati, separando la logica di business dalla logica di persistenza.

### Struttura

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
    #executeQuery(sql: string, params: array) array
}

class SpecificRepository {
    <<abstract>>
    +getById(id: int) Entity
    +create(entity: Entity) Entity
    +update(entity: Entity) bool
}

class DefaultSpecificRepository {
    +getById(id: int) Entity
    +create(entity: Entity) Entity
    +update(entity: Entity) bool
}

class Service {
    -repository: SpecificRepository
    +businessMethod() Response
}

Repository <|-- SpecificRepository
SpecificRepository <|-- DefaultSpecificRepository
Service --> SpecificRepository
```
---

## Strategy Pattern

### Scopo
Permettere di cambiare dinamicamente l'algoritmo di invio delle risposte HTTP (es. per debug, logging, formati diversi).

### Struttura

```mermaid
classDiagram
class ResponseStrategy {
    <<interface>>
    +response(response: Response)
}

class JSONResponseStrategy {
    +response(response: Response)
}

class DebugResponseStrategy {
    +response(response: Response)
}

class Router {
    -responseStrategy: ResponseStrategy
    +setResponseStrategy(strategy: ResponseStrategy)
    #sendResponse(response: Response)
}

JSONResponseStrategy ..|> ResponseStrategy
DebugResponseStrategy ..|> ResponseStrategy
Router --> ResponseStrategy
```

## Factory Pattern

### Scopo
Centralizzare la creazione dei controller, gestendo le dipendenze in modo consistente.

### Struttura

```mermaid
classDiagram
class ControllerFactory {
    -dbConnection: PDO
    +create(type: string) Controller
}

class Controller {
    <<abstract>>
    +resolveAction(action: string) Response
}

class AuthController {
    -authService: AuthService
    +resolveAction(action: string) Response
}

class FieldController {
    -fieldService: FieldService
    +resolveAction(action: string) Response
}

class BookingController {
    -bookingService: BookingService
    +resolveAction(action: string) Response
}

ControllerFactory --> Controller
AuthController --|> Controller
FieldController --|> Controller
BookingController --|> Controller
```

### Utilizzo
- `ControllerFactory::create('auth')` → crea `AuthController`
- `ControllerFactory::create('field')` → crea `FieldController`
- `ControllerFactory::create('booking')` → crea `BookingController`

---

- [Architettura Base](../Architettura%20base.md)

