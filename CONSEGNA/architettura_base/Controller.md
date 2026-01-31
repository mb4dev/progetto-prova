# Controller

Questa è la classe astratta utilizzata nel progetto per la **gestione delle richieste HTTP** nel backend.

## Descrizione

La classe astratta `Controller` definisce la struttura base per tutti i controller dell'applicazione. Fornisce metodi comuni per gestire le richieste, elaborare i dati e creare risposte.

## Struttura

```mermaid
classDiagram
class CommandController {
    <<abstract>>
    #registry: CommandRegistry
    #authMiddleware: HttpSecurity
    +resolveAction(action: string) Response
    #getBody() array
}
```

## Responsabilità

- **resolveAction()**: Esegue l'action richiesta, ottenendo il command dal registry
- **getBody()**: Recupera i dati dal body della richiesta
- **registerCommands()**: Registra i comandi disponibili (da implementare)

## Implementazioni

Le seguenti classi estendono questa classe astratta:

```mermaid
classDiagram
class CommandController {
    <<abstract>>
    #registry: CommandRegistry
    #authMiddleware: HttpSecurity
    +resolveAction(action: string) Response
    #registerCommands()*
}

class AuthController {
    -service: AuthService
    #registerCommands()
}

class ResourceController {
    -service: ResourceService
    #registerCommands()
}

class BookingController {
    -service: BookingService
    #registerCommands()
}

CommandController <|-- AuthController
CommandController <|-- ResourceController
CommandController <|-- BookingController
```

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class CommandController {
    <<abstract>>
    #registry: CommandRegistry
    #authMiddleware: HttpSecurity
    +resolveAction(action: string) Response
    #registerCommands()*
}

class Response {
    +code: int
    +success: bool
    +jsonData: array
}

class CommandRegistry {
    -commands: array
    +register(action, Command)
    +getCommand(action) Command
}

class HttpSecurity {
    <<interface>>
    +authenticate(token) User
}

class Command {
    <<abstract>>
    +execute(params, query) Response
    +getRequiredBodyParameters() array
    +getRequiredHttpMethod() string
    +requiresAuthentication() bool
}

class Factory {
    +get(className) object
}

class ControllerType {
    <<enum>>
    AUTH
    RESOURCE
    BOOKING
    +getClass() string
}

CommandController --> CommandRegistry : utilizza
CommandController --> HttpSecurity : utilizza
CommandController --> Response : restituisce
CommandRegistry --> Command : gestisce
Factory --> CommandController : crea
CommandType --> Factory : mappa classe
```

### Relazioni

- **Utilizza**: `CommandRegistry` - per ottenere il command associato all'action
- **Utilizza**: `HttpSecurity` - per autenticazione richieste protette
- **Restituisce**: `Response` - oggetto risposta HTTP
- **Creato da**: `Factory` - tramite dependency injection
- **Mappato da**: `ControllerType` enum - converte stringa controller in classe

## Flusso di Risoluzione Action

```mermaid
sequenceDiagram
    autonumber
    participant Router
    participant Controller as CommandController
    participant Registry as CommandRegistry
    participant Command
    participant Middleware as HttpSecurity

    Router ->> Controller: resolveAction(action)
    Controller ->> Registry: getCommand(action)
    Registry -->> Controller: Command

    Note over Controller: Validate HTTP method
    Controller ->> Command: validateHttpMethod(method)

    Note over Controller: Validate body
    Controller ->> Command: validateBody(body)

    Note over Controller: Check authentication
    alt requiresAuthentication()
        Controller ->> Middleware: authenticate(token)
        Middleware -->> Controller: User
    end

    Controller ->> Command: execute(body, query)
    Command -->> Controller: Response
    Controller -->> Router: Response
```