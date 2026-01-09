# Modulo Auth - Frontend

## Casi d'uso Correlati
- **UC01**: Registrazione / Autenticazione
- **UC02**: Visualizzare e Gestire Profilo Utente

Questo modulo gestisce l'autenticazione dell'utente (Login e Registrazione).

## Componenti

### AuthPresenter
Gestisce la logica di business per l'autenticazione.
- Sottoscrive all'evento `AUTH_SUBMIT_EVENT`.
- Valida i dati di input (email, password, e conferma password per la registrazione).
- Chiama il servizio di autenticazione (`login` o `register`).
- Gestisce la risposta e naviga alla rotta principale in caso di successo.

### LoginView / RegisterView
Le viste responsabili per la UI di login e registrazione.
- Emettono l'evento `AUTH_SUBMIT_EVENT` con i dati inseriti dall'utente.
- Visualizzano errori o messaggi di successo.

## Diagramma delle Classi

```mermaid
classDiagram
    class Presenter {
        <<interface>>
    }

    class AuthPresenter {
        -_view: View
        -_service: APIService
        +_handleViewEvents()
        -#handleSubmit()
        -#validateInput(eventDetail)
        -#callApi(data)
        -#handleResponse(response)
    }

    class LoginView {
        +display(data)
    }

    class RegisterView {
        +display(data)
    }

    AuthPresenter --|> Presenter
    AuthPresenter --> LoginView : manages
    AuthPresenter --> RegisterView : manages
```

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
    ValidateLogin -->|Sì| GenerateToken[Genera JWT token]

    CheckExists -->|Sì| RegisterError[Errore: Email già registrata]
    CheckExists -->|No| CreateUser[Crea nuovo utente]

    CreateUser --> DBSuccess{Inserimento OK?}
    DBSuccess -->|No| DBError[Errore database]
    DBSuccess -->|Sì| GenerateToken

    GenerateToken --> SaveToken[Salva token in cookie/storage]
    SaveToken --> Success[Autenticazione riuscita]
    Success --> Redirect[Redirect a /home]

    LoginError --> End1([Fine])
    RegisterError --> End2([Fine])
    DBError --> End3([Fine])
    Redirect --> End4([Fine])
```

## Diagrammi di Sequenza

### Flusso Login (Frontend)

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant nav as App   
    participant view as LoginView
    participant presenter as AuthPresenter
    participant bus as Observer (eventBus)
    participant api as APIService
    participant backend as Backend

    nav -->> user: mostra pagina di login
    user ->>+ view: inserisce credenziali
    view ->>+ bus: notify(LOGIN_EVENT, {email, password})
    bus ->>+ presenter: trigger callback
    
    presenter ->>+ api: login(email, password)
    api ->>+ backend: POST /auth/login
    
    alt success
        backend -->>- api: {success: true, token, user}
        api ->> api: saveToken(token)
        api -->>- presenter: {success: true, user}
        presenter ->>+ nav: notify(NAVIGATION_EVENT, {route: '/home'})
        nav -->>- user: redirect(/home)
    else error
        backend -->> api: {success: false, message}
        api -->> presenter: {success: false, message}
        presenter ->>- view: display({error: message})
        view -->> user: mostra errore
    end
    
    deactivate bus
    deactivate view
```

### Flusso Registrazione (Frontend)

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant nav as App   
    participant view as RegisterView
    participant presenter as AuthPresenter
    participant bus as Observer (eventBus)
    participant api as APIService
    participant backend as Backend

    nav -->> user: mostra pagina di registrazione
    user ->>+ view: inserisce dati (email, name, password)
    view ->>+ bus: notify(REGISTER_EVENT, {email, name, password})
    bus ->>+ presenter: trigger callback
    
    presenter ->>+ api: register(email, name, password)
    api ->>+ backend: POST /auth/register
    
    alt success
        backend -->>- api: {success: true, token, user}
        api ->> api: saveToken(token)
        api -->>- presenter: {success: true, user}
        presenter ->>+ nav: notify(NAVIGATION_EVENT, {route: '/home'})
        nav -->>- user: redirect(/home)
    else error
        backend -->> api: {success: false, message}
        api -->> presenter: {success: false, message}
        presenter ->>- view: display({error: message})
        view -->> user: mostra errore
    end
    
    deactivate bus
    deactivate view
```
