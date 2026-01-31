# Modulo auth - Backend

## Panoramica

Questo modulo gestisce l'autenticazione degli utenti tramite login e registrazione, utilizzando JWT per l'autenticazione stateless.

## Diagramma delle Classi

```mermaid
classDiagram
    class AuthController {
        -service: AuthService
        -authMiddleware: HttpSecurity
        #registerCommands()
    }

    class LoginCommand {
        -service: AuthService
        +execute(body, query)
    }

    class RegisterCommand {
        -service: AuthService
        +execute(body, query)
    }

    class AuthService {
        <<interface>>
        +login(email, password) array
        +register(name, email, password, role) array
    }

    class StandardAuthService {
        -authRepository: AuthRepository
        -passwordManager: PasswordManager
        -tokenService: TokenService
        +login(email, password)
        +register(name, email, password, role)
    }

    class AuthRepository {
        <<interface>>
        +login(email, password) User
        +register(name, email, password, role) User
        +getUserById(id) User
    }

    class PostgreAuthRepository {
        -db: PDO
        +login(email, password)
        +register(name, email, password, role)
        +getUserById(id)
    }

    class TokenService {
        <<interface>>
        +encode(user) string
        +decode(token) User
    }

    class PasswordManager {
        <<interface>>
        +validate(password, hashed) bool
        +hash(password) string
    }


    class HttpSecurity {
        <<interface>>
        +authenticate(token) User
    }

    class AuthMiddleware {
        -tokenService: TokenService
        -authRepository: AuthRepository
        +authenticate(token)
    }

    AuthController --> LoginCommand : crea
    AuthController --> RegisterCommand : crea
    AuthController --> HttpSecurity
    LoginCommand --> AuthService
    RegisterCommand --> AuthService
    StandardAuthService ..|> AuthService
    StandardAuthService --> AuthRepository
    StandardAuthService --> PasswordManager
    StandardAuthService --> TokenService
    PostgreAuthRepository ..|> AuthRepository
    AuthMiddleware ..|> HttpSecurity
    AuthMiddleware --> TokenService
    AuthMiddleware --> AuthRepository
```

## Flusso di Login (Diagramma di Sequenza)

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as AuthController
    participant Command as LoginCommand
    participant Service as StandardAuthService
    participant Repo as PostgreAuthRepository
    participant DB as PostgreSQL
    participant JWT as JwtTokenService

    Client ->> Router: POST /auth/login {email, password}
    Router ->> Controller: resolveAction("login")
    Controller ->> Command: execute(body)

    Command ->> Service: login(email, password)
    Service ->> Repo: login(email, password)
    Repo ->> DB: query
    DB -->> Repo: user row
    Repo -->> Service: User object

    Note over Service: Password validation con PasswordManager

    Service ->> JWT: encode(user)
    JWT -->> Service: JWT token

    Service -->> Command: {token, user}
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {token, user}
```

## Flusso di Registrazione (Diagramma di Sequenza)

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as AuthController
    participant Command as RegisterCommand
    participant Service as StandardAuthService
    participant Repo as PostgreAuthRepository
    participant DB as PostgreSQL
    participant JWT as JwtTokenService

    Client ->> Router: POST /auth/register {name, email, password}
    Router ->> Controller: resolveAction("register")
    Controller ->> Command: execute(body)

    Command ->> Service: register(name, email, password)
    Service ->> Repo: register(name, email, hashedPassword, role)
    Repo ->> DB: query
    DB -->> Repo: new user id
    Repo -->> Service: User object

    Service ->> JWT: encode(user)
    JWT -->> Service: JWT token

    Service -->> Command: {token, user}
    Command -->> Controller: Response(201, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 201 {token, user}
```