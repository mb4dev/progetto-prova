# Design Patterns Backend

Questo documento descrive i principali design pattern implementati nell'architettura backend del sistema.

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

## Strategy Pattern (Backend)

### Scopo
Permettere di cambiare dinamicamente l'algoritmo di invio delle risposte HTTP (es. per debug, logging, formati diversi).

### Struttura

```mermaid
classDiagram
class ResponseStrategy {
    <<interface>>
    +response(response: Response)
}

class HttpResponseStrategy {
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

---

## Factory Pattern (Backend)

### Scopo
Centralizzare la creazione dei controller, gestendo le dipendenze in modo consistente.

### Struttura

```mermaid

```

---

## Command Pattern (Backend)

### Scopo
Incapsulare una richiesta come un oggetto, permettendo di parametrizzare i controller con diverse azioni e validazioni.

### Struttura

```mermaid
classDiagram
class Command {
    <<interface>>
    +execute(body, query) Response
    +validateHttpMethod(method) bool
    +validateBody(body) bool
}

class LoginCommand {
    -service: AuthService
    +execute(body, query) Response
}

class CommandController {
    <<abstract>>
    #registry: CommandRegistry
    +resolveAction(action) Response
}

LoginCommand ..|> Command
CommandController --> CommandRegistry
CommandRegistry --> Command
```

---

## Flusso Request/Response

Il seguente diagramma mostra come i pattern backend collaborano per gestire una richiesta HTTP.

### Diagramma di Sequenza

```mermaid 
sequenceDiagram
    autonumber

    participant Client
    participant Router
    participant URLParser
    participant ControllerFactory
    participant Registry as ControllerCreatorRegistry
    participant Creator as ControllerCreator
    participant Controller as CommandController
    participant Command
    participant ResponseStrategy

    Client ->>+ Router: HTTP Request
    Router ->>+ URLParser: parse()
    URLParser -->>- Router: ParsedURL

    Router ->>+ ControllerFactory: create(controllerType)
    ControllerFactory ->>+ Registry: get(type)
    Registry -->>- ControllerFactory: ControllerCreator
    ControllerFactory ->>+ Creator: create(db)
    Creator -->>- ControllerFactory: CommandController
    ControllerFactory -->>- Router: CommandController

    Router ->>+ Controller: resolveAction(action)
    Controller ->>+ Command: execute(body, query)
    Command -->>- Controller: Response
    Controller -->>- Router: Response

    Router ->>+ ResponseStrategy: response(Response)
    ResponseStrategy -->>- Client: HTTP Response
```
