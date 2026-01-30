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

    class JwtTokenService {
        -secret: string
        -algorithm: JwtAlgorithm
        -expirationSeconds: int
        +encode(user)
        +decode(token)
    }

    class PasswordManager {
        <<interface>>
        +validate(password, hashed) bool
        +hash(password) string
    }

    class DefaultPasswordManager {
        +validate(password, hashed)
        +hash(password)
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
    JwtTokenService ..|> TokenService
    DefaultPasswordManager ..|> PasswordManager
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
    Repo ->> DB: SELECT * FROM centro_sportivo.utenti WHERE email = ?
    DB -->> Repo: user row
    Repo -->> Service: User object

    Note over Service: Password validation with PasswordManager

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
    Repo ->> DB: INSERT INTO centro_sportivo.utenti (name, email, password, role)
    DB -->> Repo: new user id
    Repo -->> Service: User object

    Service ->> JWT: encode(user)
    JWT -->> Service: JWT token

    Service -->> Command: {token, user}
    Command -->> Controller: Response(201, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 201 {token, user}
```

## Endpoint

| Metodo | Path | Descrizione |
|--------|------|-------------|
| POST | `/auth/login` | Login utente |
| POST | `/auth/register` | Registrazione nuovo utente |

## Comandi

### LoginCommand

```php
class LoginCommand extends Command {
    public function __construct(private AuthService $service) {}
    public function execute(array $params, array $query = []): Response {
        $result = $this->service->login($params['email'], $params['password']);
        return new Response(200, true, $result);
    }
    public function getRequiredBodyParameters(): array { return ['email', 'password']; }
    public function getRequiredQueryParameters(): array { return []; }
    public function getRequiredHttpMethod(): string { return 'post'; }
    public function requiresAuthentication(): bool { return false; }
    public function getRequiredRoles(): array { return []; }
}
```

### RegisterCommand

```php
class RegisterCommand extends Command {
    public function __construct(private AuthService $service) {}
    public function execute(array $params, array $query = []): Response {
        $result = $this->service->register(
            $params['name'],
            $params['email'],
            $params['password'],
            Role::from($params['role'] ?? 'user')
        );
        return new Response(201, true, $result);
    }
    public function getRequiredBodyParameters(): array { return ['name', 'email', 'password']; }
    public function getRequiredQueryParameters(): array { return []; }
    public function getRequiredHttpMethod(): string { return 'post'; }
    public function requiresAuthentication(): bool { return false; }
    public function getRequiredRoles(): array { return []; }
}
```

## Dipendenze del Modulo

```mermaid
classDiagram
    class AuthController {
        <<depends on>>
    }
    class AuthService {
        <<depends on>>
    }
    class AuthRepository {
        <<depends on>>
    }
    class TokenService {
        <<depends on>>
    }
    class PasswordManager {
        <<depends on>>
    }
    class HttpSecurity {
        <<depends on>>
    }
```

## Note

- Il modulo utilizza **PostgreSQL** come database
- La password viene hashata con `password_hash()` (cost 10)
- I token JWT hanno scadenza di 1 ora (3600 secondi)
- I ruoli supportati sono `user` e `admin`
- L'autenticazione è stateless tramite Bearer token

## Flusso di Login (Diagramma di Sequenza)

Il seguente diagramma mostra il flusso di un'operazione di login andata a buon fine.

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as DefaultRouter
    participant Controller as AuthController
    participant Command as LoginCommand
    participant Service as DefaultAuthService
    participant Repo as DefaultAuthRepository
    participant DB as MySQL

    Client ->> Router: POST /auth/login {email, password}
    Router ->> Controller: resolveAction("login")
    Controller ->> Command: execute(body)
    
    Command ->> Service: login(email, password)
    Service ->> Repo: login(email, password)
    Repo ->> DB: SELECT * FROM utenti WHERE email = ?
    DB -->> Repo: user row
    Repo -->> Service: User object
    
    Note over Service: Password validation & JWT generation
    
    Service -->> Command: array{token, user}
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {token, user}
```


