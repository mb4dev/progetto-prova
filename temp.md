# Modifiche ai Diagrammi

In base all'analisi del codice presente nella cartella `implementazione/frontend`, ecco le proposte di modifica ai diagrammi.

## Modifiche ai Diagrammi di Sequenza

Il codice implementa un pattern **Model-View-Presenter (MVP)** per il frontend, separando la logica di visualizzazione (`LoginView`) dalla logica di gestione eventi (`LoginPresenter`). I diagrammi di sequenza dovrebbero riflettere questa separazione invece di mostrare un generico attore `GUI`.

### UC01 - Autenticazione (Frontend Flow)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant View as LoginView
    participant Presenter as LoginPresenter
    participant Backend as MockBackend/API

    Utente ->> View: Inserisce email e password
    Utente ->> View: Clicca su "Login"
    View ->> View: Intercetta click
    View ->> Presenter: Dispatch Event "login-submit" (email, password)
    Presenter ->> Presenter: Riceve evento
    
    note right of Presenter: Attualmente il codice logga solo i dati. <br/>In futuro qui avverrà la chiamata al backend.
    
    %% Esempio di flusso futuro basato su MockBackend
    %% Presenter ->> Backend: login(email, password)
    %% Backend -->> Presenter: token
```

## Diagrammi di Classe per il Frontend

Di seguito il diagramma delle classi basato su `app.js`.

### Class Diagram - Authentication Module

```mermaid
classDiagram
    class HTMLElement {
        <<Native>>
    }

    class LoginView {
        -HTMLElement #submitBtn
        +connectedCallback()
        +display()
        +template() String
        +registerEvents()
    }
    
    %% LoginView estende HTMLElement (Web Component)
    HTMLElement <|-- LoginView

    class Presenter {
        <<Abstract>>
        #Object _view
        +constructor(view)
        +update()*
        +_registerEvents()*
    }

    class LoginPresenter {
        +constructor(view)
        +_registerEvents()
    }

    %% LoginPresenter estende Presenter
    Presenter <|-- LoginPresenter

    class MockBackend {
        -String #token
        +login(email, password) String
    }
    
    %% Relazioni
    LoginPresenter --> LoginView : osserva
    
    note for LoginView "Implementa Custom Element <login-view>"
    note for Presenter "Classe base che impone interfaccia alla View"
```
