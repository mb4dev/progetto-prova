# Controller (Classe Astratta)

Questa è la classe astratta utilizzata nel progetto per la **gestione delle richieste HTTP** nel backend.

## Descrizione

La classe astratta `Controller` definisce la struttura base per tutti i controller dell'applicazione. Fornisce metodi comuni per gestire le richieste, elaborare i dati e creare risposte.

## Struttura

```mermaid
classDiagram
class Controller {
    <<abstract>>
    +resolveAction(action: string) Response
    #getBody() array
}
```

## Responsabilità

- **resolveAction()**: Esegue l'action richiesta (metodo astratto da implementare)
- **getBody()**: Recupera i dati dal body della richiesta

## Implementazioni

Le seguenti classi estendono questa classe astratta:

```mermaid
classDiagram
class Controller {
    <<abstract>>
    +resolveAction(action: string) Response
    #getBody() array
}

class AuthController {
    -authService: AuthService
    +resolveAction(action: string) Response
    -handleLogin() Response
    -handleRegister() Response
}

class UserController {
    -userService: UserService
    +resolveAction(action: string) Response
    -handleGetProfile() Response
    -handleUpdateProfile() Response
}

class FieldController {
    -fieldService: FieldService
    +resolveAction(action: string) Response


}

class BookingController {
    -bookingService: BookingService
    +resolveAction(action: string) Response
}



AuthController --|> Controller
UserController --|> Controller
FieldController --|> Controller
BookingController --|> Controller
```

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class Controller {
    <<abstract>>
    +resolveAction(action: string) Response
    #getBody() array

    #isAuthenticated()
    #isAuthorized()
}

class Response {
    +code: int
    +success: bool
    +data: mixed
    +message: string
}

class Service {
    <<interface>>
}

class Router {
    +dispatch()
}

class ControllerFactory {
    +create(type: string) Controller
}

class AuthController {
    -authService: AuthService
}

Controller --> Response : restituisce
Controller --> Service : utilizza
Router --> Controller : invoca
ControllerFactory --> Controller : crea
AuthController --|> Controller : estende
```

### Relazioni
- **Restituisce**: `Response` - oggetto risposta HTTP
- **Utilizza**: Service layer - per logica business (AuthService, UserService, ecc)
- **Utilizzata da**: `Router` - per gestione richieste
- **Creata da**: `ControllerFactory` - factory pattern
- **Estesa da**: `AuthController`, `UserController`,

