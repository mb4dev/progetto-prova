# Modulo Auth - Frontend

## Casi d'uso Correlati
- **UC01**: Registrazione / Autenticazione
- **UC02**: Visualizzare e Gestire Profilo Utente


## Diagramma di Attività

```mermaid 
flowchart TD
    Start([Inizio]) --> Choice{Tipo operazione}

    Choice -->|Login| LoginInput[Inserisce email e password]
    Choice -->|Register| RegisterInput[Inserisce email, username e password]

    LoginInput --> SubmitLogin[Invia richiesta login]
    RegisterInput --> SubmitRegister[Invia richiesta register]

    SubmitLogin --> ValidateLogin{Credenziali valide?}
    SubmitRegister --> CheckExists{Email già esistente?}

    ValidateLogin -->|No| LoginError[Mostra errore login]
    ValidateLogin -->|Sì| SaveToken[Genera JWT token]

    CheckExists -->|Sì| RegisterError[Errore: Email già registrata]
    CheckExists -->|No| CreateUser[Crea nuovo utente]

    CreateUser --> DBSuccess{Inserimento OK?}
    DBSuccess -->|No| DBError[Errore database]

    DBSuccess --> SaveToken[Salva token in cookie/storage]
    SaveToken --> Success[Autenticazione riuscita]
    Success --> Redirect[Redirect a /home]

    LoginError --> End1([Fine])
    RegisterError --> End2([Fine])
    DBError --> End3([Fine])
    Redirect --> End4([Fine])
```



## Diagrammi di Sequenza

### Flusso Login - Registrazione (Frontend)

```mermaid
sequenceDiagram    
    actor user as Utente
    participant nav as App   
    participant view as LoginView
    participant presenter as AuthPresenter
	participant strategy as AuthStrategy
    participant bus as Observer
    participant backend as Backend

    nav -->>+ user: mostra pagina Login/Registrazione
    user ->>+ view: inserisce credenziali
    view ->>+ bus: notify(EVENT, data)
    bus -->>+ presenter : event callback
	presenter ->> strategy : validate()
    presenter ->> strategy: authenticate(data)
    strategy ->>+ backend: POST /api/...
    
    alt success
        backend -->> strategy: {success: true, token, user}
		strategy -->> presenter: success
        presenter -->> nav: execute navigation command
        nav -->> user: mostra pagina home
    else error
        backend -->> strategy: {success: false, message}
		strategy -->> presenter : error
        presenter ->>- view: display({error: message})
        view -->> user: mostra errore
    end
    
    deactivate bus
    deactivate view
```

## Diagramma delle Classi

``` mermaid 

classDiagram
    class View {
        <<abstract>>
        #config: Object
        +display(data) 
        +template() string
        #bindEvents() 
    }

    class LoginView {
    }

    class RegisterView {
    }

    class Presenter {
        <<abstract>>
        _view : View
        _config: Object
        +update() void
        #handleViewEvents() void
    }

    class AuthPresenter {
    }

    class APIService {
        <<interface>>
    }

    class AuthStrategy {
        <<interface>>
        +authenticate(data)
        +validate(data)
    }

    class Command {
        <<interface>>
        +execute()
    }

    LoginView --|> View 
    RegisterView --|> View 
    AuthPresenter --|> Presenter 
    
    LoginStrategy ..|> AuthStrategy 
    RegisterStrategy ..|> AuthStrategy
    NavigateCommand ..|> Command 

    Presenter --> View 
    LoginStrategy --> APIService 
    RegisterStrategy --> APIService
    AuthPresenter o-- Command 
    AuthPresenter o-- AuthStrategy
```