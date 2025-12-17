# Architettura Base del Sistema

## Backend

L'architettura backend segue un pattern MVC con separazione netta tra Routing, Controller, Service e Repository.

### Core Components

```mermaid
classDiagram
    %% Core Classes
    class Controller {
        <<abstract>>
        +resolveAction(action: string)
        #response(response: Response)
    }

    class ControllerFactory {
        +create(controllerName: string) Controller
    }

    class URLParser {
        +parse(url: string) ParsedURL
    }

    class ParsedURL {
        +controller: string
        +action: string
        +params: array
    }

    class Router {
        -parser: URLParser
        -factory: ControllerFactory
        +dispatch(url: string)
    }

    class Response {
        +code: int
        +success: bool
        +jsonData: string
    }
    
    %% Relationships
    Router --> ControllerFactory
    Router --> URLParser
    URLParser *-- ParsedURL
    ControllerFactory ..> Controller : Creates
    Controller ..> Response
```

### Gerarchia Controller

Tutti i controller del sistema estendono la classe astratta `Controller`.

```mermaid
classDiagram
    class Controller {
        <<abstract>>
        +resolveAction(action: string)
        #response(response: Response)
    }

    %% Concrete Controllers
    class AuthController
    class ProfileController
    class AvailabilityController
    class BookingController
    class CourseController
    class PaymentController
    class SubscriptionController
    class HistoryController
    class AdminFacilityController
    class AdminCourseController
    class AdminTariffController

    %% Inheritance
    AuthController --|> Controller
    ProfileController --|> Controller
    AvailabilityController --|> Controller
    BookingController --|> Controller
    CourseController --|> Controller
    PaymentController --|> Controller
    SubscriptionController --|> Controller
    HistoryController --|> Controller
    AdminFacilityController --|> Controller
    AdminCourseController --|> Controller
    AdminTariffController --|> Controller
```

### Services e Repositories

I servizi e i repository sono definiti tramite interfacce per garantire il disaccoppiamento.

```mermaid
classDiagram
    %% Base Interfaces
    class Service {
        <<interface>>
    }
    
    class Repository {
        <<interface>>
    }

    %% Example Implementations Structure
    class AuthService {
        <<interface>>
        +login(user)
        +register(user)
    }
    
    class DefaultAuthService {
        -repository: AuthRepository
        -tokenManager: JwtTokenManager
        -passChecker: PasswordValidator
    }
    
    class AuthRepository {
        <<interface>>
    }
    
    class DefaultAuthRepository {
        -connection: PDOConnection
    }
    
    class JwtTokenManager {
        <<interface>>
        +encode(data)
        +decode(token)
    }
    
    class PasswordValidator {
        <<interface>>
        +validate(password, hash)
    }

    DefaultAuthService --|> AuthService
    DefaultAuthRepository --|> AuthRepository
    DefaultAuthService --> AuthRepository
    DefaultAuthService --> JwtTokenManager
    DefaultAuthService --> PasswordValidator
    
    %% Generalized Pattern for other services
    Service <|.. AuthService
    Repository <|.. AuthRepository
```

## Frontend

L'architettura frontend implementa il pattern MVP (Model-View-Presenter) con Observer per la gestione degli eventi.

```mermaid
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
```
