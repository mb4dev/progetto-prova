# Presenter (Classe Astratta)

Questa è la classe astratta utilizzata nel progetto per la **logica di presentazione** nel pattern MVP (Model-View-Presenter).

## Descrizione

La classe astratta `Presenter` definisce la struttura base per gestire la logica di presentazione dell'applicazione frontend. Funge da intermediario tra la View e i servizi (API).

## Struttura

```mermaid
classDiagram
class Presenter {
    <<abstract>>
    #view: View
    #config: Object
    #api: APIService
    +init() 
    #update()
    #handleViewEvents()
}
```

## Responsabilità

- **init()**: Inizializza il presenter e la view
- **update()**: Aggiorna la view con nuovi dati
- **handleViewEvents()**: Gestisce gli eventi provenienti dalla view

## Pattern

Implementa la parte **Presenter** del pattern **MVP (Model-View-Presenter)** con comunicazione tramite **Observer Pattern**.

## Implementazioni

Le seguenti classi estendono questa classe astratta:

```mermaid
classDiagram
class Presenter {
    <<abstract>>
    #view: View
    #api: APIService
    +init()
    +update()
    #handleViewEvents()
}

class AuthPresenter {
    +init()
    +update()
    #handleViewEvents()
    -validateInput(data: Object) bool
    -handleResponse(response: Object)
}

class MainPresenter {
    -registry: Object
    +init()
    +update()
    #handleViewEvents()
    -loadSubPresenter(route: string)
}

class ProfilePresenter {
    +init()
    +update()
    #handleViewEvents()
    -loadProfile()
    -updateProfile(data: Object)
}

AuthPresenter --|> Presenter
MainPresenter --|> Presenter
ProfilePresenter --|> Presenter
```

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class Presenter {
    <<abstract>>
    #view: View
    #api: APIService
    +init()
    +update()
    #handleViewEvents()
}

class View {
    <<interface>>
    +display(data)
}

class APIService {
    <<interface>>
    +login() Promise
    +getProfile() Promise
}

class Observer {
    <<interface>>
    +subscribe(event: string, callback: Function)
}

class AuthPresenter {
    +init()
}

Presenter --> View : utilizza
Presenter --> APIService : utilizza
Presenter ..> Observer : utilizza per subscribe
AuthPresenter --|> Presenter : estende
```

### Relazioni
- **Utilizza**: `View` - per aggiornare l'interfaccia utente
- **Utilizza**: `APIService` - per comunicare con il backend
- **Utilizza**: `Observer` - per sottoscriversi agli eventi della view
- **Estesa da**: `AuthPresenter`, `MainPresenter`, `CampiPresenter`, `BookingPresenter`, `ProfilePresenter`

