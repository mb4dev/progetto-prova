# Design Patterns Backend

Questo documento descrive i principali design pattern implementati nell'architettura backend del sistema.

---

## Repository Pattern

### Scopo
Astrarre l'accesso ai dati, separando la logica di business dalla logica di persistenza.

### Struttura

```mermaid
classDiagram
class AuthRepository {
    <<interface>>
    +getUserById(id: int) User
    +login(email: string, password: string) User
    +register(name: string, email: string, password: string, role: Role) User
}

class BookingRepository {
    <<interface>>
    +getBooking(resourceId: int, date: string, slot: string)
    +insertBooking(userId: int, resourceId: int, date: string, slot: string) int
}

class ResourcesRepository {
    <<interface>>
    +getAll() array
    +getResourceById(id: int) array
}

class PostgreAuthRepository {
    -db: PDO
    +getUserById(id)
    +login(email, password)
    +register(name, email, password, role)
}

class FieldBookingRepository {
    -db: PDO
    +getBooking(resourceId, date, slot)
    +insertBooking(userId, resourceId, date, slot)
}

class PostgreFieldsRepository {
    -db: PDO
    +getAll()
    +getResourceById(id)
}

class PostgreCoursesRepository {
    -db: PDO
    +getAll()
    +getResourceById(id)
}

class Service {
    -repository: Repository
    +businessMethod() Response
}

AuthRepository <|.. PostgreAuthRepository
BookingRepository <|.. FieldBookingRepository
ResourcesRepository <|.. PostgreFieldsRepository
ResourcesRepository <|.. PostgreCoursesRepository
Service --> AuthRepository
Service --> BookingRepository
Service --> ResourcesRepository
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
    -corsOrigin: string
    +response(response: Response)
}

class ConsoleResponseStrategy {
    +response(response: Response)
}

class Router {
    -responseStrategy: ResponseStrategy
    +setResponseStrategy(strategy: ResponseStrategy)
    #sendResponse(response: Response)
}

HttpResponseStrategy ..|> ResponseStrategy
ConsoleResponseStrategy ..|> ResponseStrategy
Router --> ResponseStrategy
```

---

## Factory Pattern (Backend)

### Scopo
Centralizzare la creazione degli oggetti, gestendo le dipendenze in modo consistente tramite dependency injection.

### Struttura

```mermaid
classDiagram
class Factory {
    -factories: array
    -instances: array
    +register(className: string, FactoryMethod)
    +get(className: string) object
}

class FactoryMethod {
    <<interface>>
    __invoke(factory: Factory) object
}

class ControllerType {
    <<enum>>
    AUTH
    RESOURCE
    BOOKING
    +getClass() string
}

class AuthController {
    +__construct(middleware, service)
}

class ResourceController {
    +__construct(middleware, service)
}

class BookingController {
    +__construct(middleware, service)
}

class StandardAuthService {
    +__construct(repo, passwordManager, tokenService)
}

class JwtTokenService {
    +__construct(secret, algorithm, expiration)
}

Factory --> FactoryMethod : usa
ControllerType --> Factory : mappa a classe
Factory --> AuthController : crea
Factory --> ResourceController : crea
Factory --> BookingController : crea
Factory --> StandardAuthService : crea
Factory --> JwtTokenService : crea
```

### Flusso di Creazione

```mermaid
sequenceDiagram
    autonumber
    participant Config
    participant Factory
    participant FactoryMethod
    participant Dependency

    Config ->> Factory: register(Interface::class, FactoryMethod)
    
    Note over Factory: Quando serve un'istanza
    
    Factory ->> FactoryMethod: __invoke(factory)
    FactoryMethod ->> Factory: get(Dependency::class)
    Factory -->> FactoryMethod: Dependency instance
    FactoryMethod -->> Factory: new Instance()
    Factory ->> Factory: instances[className] = instance
    Factory -->> Config: instance
```

---

## Command Pattern (Backend)

### Scopo
Incapsulare una richiesta come un oggetto, permettendo di parametrizzare i controller con diverse azioni, validazioni e controllo accessi.

### Struttura

```mermaid
classDiagram
class Command {
    <<abstract>>
    +execute(params: array, query: array) Response
    +getRequiredBodyParameters() array
    +getRequiredQueryParameters() array
    +getRequiredHttpMethod() string
    +requiresAuthentication() bool
    +getRequiredRoles() array
    +validateBody(body: array)
    +validateQueryParameters(query: array)
    +validateHttpMethod(method: string)
}

class LoginCommand {
    -service: AuthService
    +execute(body, query)
    +getRequiredBodyParameters() array
    +requiresAuthentication() bool
}

class RegisterCommand {
    -service: AuthService
    +execute(body, query)
}

class InsertFieldBookingCommand {
    -service: BookingService
    +execute(body, query)
}

class GetAllResourceCommand {
    -service: ResourceService
    +execute(body, query)
}

class CommandController {
    <<abstract>>
    #registry: CommandRegistry
    #authMiddleware: HttpSecurity
    +resolveAction(action: string) Response
    #registerCommands()*
}

class CommandRegistry {
    -commands: array
    +register(action: string, Command)
    +getCommand(action: string) Command
}

Command <|-- LoginCommand
Command <|-- RegisterCommand
Command <|-- InsertFieldBookingCommand
Command <|-- GetAllResourceCommand
CommandController --> CommandRegistry
CommandRegistry --> Command
```

### Flusso di Esecuzione

```mermaid
sequenceDiagram
    autonumber
    participant Controller as CommandController
    participant Registry as CommandRegistry
    participant Command
    participant Middleware as HttpSecurity

    Controller ->> Registry: getCommand(action)
    Registry -->> Controller: Command
    
    Note over Controller: Validazione HTTP method
    Controller ->> Command: validateHttpMethod(method)
    
    Note over Controller: Validazione body
    Controller ->> Command: validateBody(body)
    
    Note over Controller: Validazione query
    Controller ->> Command: validateQueryParameters(query)
    
    alt requiresAuthentication()
        Note over Controller: Estrai token
        Controller ->> Command: requiresAuthentication()
        
        alt ha bisogno di autenticazione
            Controller ->> Middleware: authenticate(token)
            Middleware -->> Controller: User
        end
    end
    
    Controller ->> Command: execute(body, query)
    Command -->> Controller: Response
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
    participant Factory
    participant Controller as CommandController
    participant Command
    participant ResponseStrategy

    Client ->>+ Router: HTTP Request
    Router ->>+ URLParser: parse(requestUri)
    URLParser -->>- Router: ParsedURL{controller, action}
    
    Router ->> Factory: get(ControllerType.getClass())
    Factory -->> Router: CommandController instance
    
    Router ->>+ Controller: resolveAction(action)
    Controller ->> Controller: getCommand(action)
    Controller ->> Command: validateHttpMethod(method)
    Controller ->> Command: validateBody(body)
    
    alt requiresAuthentication()
        Note over Controller: Get token from header
        Controller ->> Command: requiresAuthentication()
    end
    
    Controller ->>+ Command: execute(body, query)
    Command -->>- Controller: Response
    Controller -->>- Router: Response

    Router ->>+ ResponseStrategy: response(Response)
    ResponseStrategy -->>- Client: HTTP Response
```

### Classi Coinvolte nel Flusso

```mermaid
classDiagram
    class Router {
        <<abstract>>
        +dispatch()
    }
    
    class StandardRouter {
        +dispatch()
    }
    
    class URLParser {
        <<interface>>
        +parse(uri) ParsedURL
    }
    
    class StandardURLParser {
        +parse(uri)
    }
    
    class ResponseStrategy {
        <<interface>>
        +response(Response)
    }
    
    class HttpResponseStrategy {
        +response(Response)
    }
    
    class Factory {
        +get(className)
    }
    
    class CommandController {
        <<abstract>>
        +resolveAction(action)
    }
    
    class AuthController {
        #registerCommands()
    }
    
    class CommandRegistry {
        +register(action, Command)
        +getCommand(action)
    }
    
    class Command {
        <<abstract>>
        +execute(params, query)
    }
    
    StandardRouter --|> Router
    StandardURLParser ..|> URLParser
    HttpResponseStrategy ..|> ResponseStrategy
    StandardRouter --> Factory
    StandardRouter --> URLParser
    StandardRouter --> ResponseStrategy
    Factory --> CommandController
    AuthController --|> CommandController
    CommandController --> CommandRegistry
    CommandRegistry --> Command
```
