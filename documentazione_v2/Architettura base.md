
I seguenti diagrammi mostrano come viene gestita una chiamata HTTP dal backend. 
Per la creazione del controller associato alla chiamata viene utilizzato una factory.
Per motivi di debug, per l'invio della risposta al client viene utilizzata il design pattern "Strategy" 
``` mermaid 
sequenceDiagram
    autonumber

    participant Client
    participant Router
    participant URLParser
    participant ControllerFactory
    participant Controller
    participant ResponseStrategy

    Client ->> Router: HTTP Request
    Router ->> URLParser: parse()
    URLParser -->> Router: ParsedURL

    Router ->> ControllerFactory: create(controllerType)
    ControllerFactory -->> Router: Controller

    Router ->> Controller: resolveAction(action)
    Controller -->> Router: Response

    Router ->> ResponseStrategy: response(Response)
    ResponseStrategy -->> Client: HTTP Response

```

``` mermaid 

classDiagram

%% =====================
%% Core Infrastructure
%% =====================

class Router {
    <<abstract>>
    #urlParser: URLParser
    #controllerFactory: ControllerFactory
    #responseStrategy: ResponseStrategy

    +dispatch(): void
    #sendResponse(response: Response): void
}

class DefaultRouter {
    +dispatch(): void
}

Router <|-- DefaultRouter

%% =====================
%% Supporting Components
%% =====================

class URLParser {
    <<interface>>
    +parse(): ParsedURL
}

class ResponseStrategy {
    <<interface>>
    +response(response: Response): void
}

class ControllerFactory {
    -dbConnection: PDO
    +create(type): Controller
}

%% =====================
%% Dependencies
%% =====================

Router --> URLParser
Router --> ControllerFactory
Router --> ResponseStrategy

ControllerFactory --> PDO
ControllerFactory --> Controller

```

Il frontend implementa il pattern MVP (Model-View-Presenter) con Observer per la gestione degli eventi.

``` mermaid 
classDiagram
    class View {
        <<interface>>
        +display()
        +template() string
        #bindEvents()
    }
    
    class Presenter {
        <<class>>
        #view: View
        #api: APIService
        +init()
        +update()
        #handleViewEvents()
    }
    
    class APIService {
        <<interface>>
        +login(email, password)
        +register(email, name, password)
    }
    
    class Observer {
        <<interface>>
        +subscribe(event, callback)
        +notify(event, data)
    }
    
    class DefaultObserver {
        -listeners: Map
    }
    
    class MockAPIService
    
    %% Relationships
    DefaultObserver --|> Observer
    MockAPIService --|> APIService
    Presenter --> View
    Presenter --> APIService
    
    %% Concrete Implementations (Examples)
    class AuthPresenter {
        +update()
    }
    
    class LoginView
    class RegisterView
    
    AuthPresenter --|> Presenter
    LoginView --|> View
    RegisterView --|> View
    
    AuthPresenter --> LoginView
    AuthPresenter --> RegisterView
    
    %% Observer Usage
    View ..> DefaultObserver
    Presenter ..> DefaultObserver

---

### Gestione Navigazione (MainView & MainPresenter)

Il sistema di navigazione principale è gestito dal `MainPresenter`, che agisce come orchestratore per le diverse sezioni dell'applicazione.

#### Diagramma di Classe
``` mermaid
classDiagram
    direction LR

    class MainView {
        -mainContent: HTMLElement
        -activeRoute: string
        +display(data)
        -bindEvents()
        -updateActiveTab()
    }

    class MainPresenter {
        -registry: Object
        +init()
        -handleViewEvents()
    }

    class SubPresenter {
        <<interface>>
        +init()
    }

    class SubView {
        <<interface>>
        +connectedCallback()
    }

    MainView --|> View
    MainPresenter --|> Presenter
    MainPresenter --> MainView
    MainPresenter ..> SubPresenter : Creates
    MainPresenter ..> SubView : Creates
    MainView ..> DefaultObserver : Notifies (Events.MAIN_SELECT_EVENT)
    MainPresenter ..> DefaultObserver : Subscribes
```

#### Diagramma di Sequenza (Navigazione)
``` mermaid
sequenceDiagram
    autonumber

    participant User
    participant SideMenu as MainView (SideMenu)
    participant Observer as DefaultObserver (eventBus)
    participant MainP as MainPresenter
    participant SubP as SubPresenter (e.g., ProfileP)
    participant SubV as SubView (e.g., ProfileV)
    participant MainContent as MainView (Content Area)

    User ->> SideMenu: Click Navigation Button
    SideMenu ->> Observer: notify(MAIN_SELECT_EVENT, {main: route})
    Observer ->> MainP: trigger callback
    
    Note over MainP: Look up route in registry
    
    MainP ->> SubV: new SubView()
    MainP ->> SubP: new SubPresenter(view, service)
    MainP ->> SubP: init()
    MainP ->> MainContent: display({view, route})
    
    MainContent ->> MainContent: Update active tab styling
    MainContent ->> MainContent: Append SubView to DOM
```
```
