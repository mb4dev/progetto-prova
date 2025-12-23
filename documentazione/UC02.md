	# UC02 - Visualizzare e Gestire Profilo Utente

Per inizializzazione dei componenti o componenti comuni a tutto il software vedere [Architettura base](./Architettura%20base.md)

---

## Diagrammi delle Classi

### Backend - Repository Layer

```mermaid 
classDiagram

class Repository {
    <<abstract>>
}

class UserRepository {
    <<abstract>>
    +getById(id: int) User
    +update(user: User)
}

class DefaultUserRepository {
    +getById(id: int) User
    +update(user: User)
}

UserRepository --|> Repository        
DefaultUserRepository --|> UserRepository  
Repository --> PDO : usa

DefaultUserRepository ..> UserNotFoundException : throws
UserNotFoundException --|> Exception
```

### Backend - Service Layer

```mermaid 
classDiagram

class UserService {
    <<interface>>
    +getProfile(id: int) Response
    +updateProfile(id: int, data: Object) Response
}

class DefaultUserService {
    -userRepository: UserRepository
    +getProfile(id: int) Response
    +updateProfile(id: int, data: Object) Response
}
class UserRepository {
    <<interface>>
}

DefaultUserService ..|> UserService
DefaultUserService --> UserRepository
UserService --> Response
```

### Backend - Controller Layer

```mermaid
classDiagram

class Controller {
    <<interface>>
}

class UserController {
    -userService: UserService
    +resolveAction(action: string) Response
}

UserController --|> Controller
UserController --> UserService
Controller --> Response
```

### Frontend

```mermaid 
classDiagram

    class ProfileView {
        -profileData: Object
        -isEditing: bool
        +display(data: Object) void
        +render() void
        #bindEvents() void
    }

    class Presenter {
        <<interface>>
    }

    class ProfilePresenter {
        +init() void
        +update() void
        #handleViewEvents() void
    }

    class APIService {
        <<interface>>
    }

    class View {
        <<interface>>
    }

    class Observer {
        <<interface>>
    }

    ProfileView ..|> View
    ProfilePresenter --|> Presenter
    Presenter --> View
    Presenter --> APIService
    
    ProfilePresenter ..> Observer : subscribe
    ProfileView ..> Observer : notify
```

---

## Diagramma di Attività

```mermaid 
flowchart TD
    Start([Inizio]) --> MainView[Apertura MainView]
    MainView --> LoadDefault[Caricamento sezione Profilo]
    LoadDefault --> GetProfile[Richiesta dati profilo]

    GetProfile --> ViewProfile[Mostra dati profilo]
    ViewProfile --> Choice{Cosa fare?}

    Choice -->|Modifica| EditMode[Attiva modalità modifica]
    Choice -->|Esci| End([Fine])

    EditMode --> EditInput[Inserisce nuovi dati]
    EditInput --> Save[Clicca Salva]

    Save --> Validate{Dati validi?}
    Validate -->|No| ShowError[Mostra errore validazione]
    ShowError --> EditInput

    Validate -->|Sì| UpdateReq[Invia richiesta aggiornamento]
    UpdateReq --> DBCheck{Salvataggio OK?}
    DBCheck -->|No| DBError[Mostra errore sistema]
    DBCheck -->|Sì| Success[Aggiorna vista]
    
    Success --> ViewProfile
    DBError --> EditMode
```

---

## Diagrammi di Sequenza

### Frontend

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant main as MainPresenter
    participant view as ProfileView
    participant presenter as ProfilePresenter
    participant bus as Observer (eventBus)
    participant api as APIService
    participant backend as Backend

    main ->>+ presenter: init()
    presenter ->>+ api: getProfile()
    api ->>+ backend: GET /user/profile
    
    alt success
        backend -->>- api: {success: true, data: {user}}
        api -->>- presenter: {success: true, data: {user}}
        presenter ->>+ view: display({profile: data, mode: "view"})
        view -->>- user: mostra dati profilo
    else error
        backend -->> api: {success: false, message}
        api -->> presenter: {success: false, message}
        presenter ->> view: display(error)
        view -->> user: mostra errore
    end
    
    user ->>+ view: modifica dati e clicca "Salva"
    view ->>+ bus: notify(PROFILE_UPDATE_EVENT, {data})
    bus ->>+ presenter: callback
    
    presenter ->>+ api: updateProfile(data)
    api ->>+ backend: POST /user/update
    
    alt success
        backend -->>- api: {success: true, data: {user}}
        api -->>- presenter: {success: true, data: {user}}
        presenter ->>+ view: display()
        view -->>- user: mostra dati aggiornati
    else error
        backend -->> api: {success: false, message}
        api -->> presenter: {success: false, message}
        presenter ->> view: display(error)
        view -->> user: mostra errore
    end
    
    deactivate presenter
    deactivate bus
    deactivate view
    deactivate presenter
```

### Backend

```mermaid
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant router as Router
    participant controller as :UserController
    participant service as :UserService
    participant repository as :UserRepository
    participant db as Database

    frontend ->>+ router: GET /user/profile
    router ->>+ controller: resolveAction("profile")
    controller ->>+ service: getProfile(userId)
    service ->>+ repository: getById(userId)
    repository ->>+ db: query
    db -->>- repository: user data
    repository -->>- service: User
    
    alt success
        service -->>- controller: Response(200, success, {user})
        controller -->>- router: Response(200, success, {user})
    else user not found
        repository -->> service: throw UserNotFoundException
        service -->> controller: Response(404, false, "User not found")
        controller -->> router: Response(404, false, "User not found")
    end
    
    router -->>- frontend: HTTP Response
```