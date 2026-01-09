# Modulo Auth - Backend

## Casi d'uso Correlati
- **UC01**: Registrazione / Autenticazione
- **UC02**: Visualizzare e Gestire Profilo Utente

Questo modulo gestisce la logica server per l'autenticazione, la validazione delle credenziali e la gestione delle sessioni (JWT).

## Diagramma delle Classi

### Repository Layer
```mermaid 
classDiagram

class Repository {
    <<Repository>>
}

class AuthRepository {
    <<abstract>>
    +login(email: string, password: string) User
    +register(name: string, email: string, password: string) User
}

class DefaultAuthRepository {
    +login(email: string, password: string) User
    +register(name: string, email: string, password: string) User
}

class UserNotFoundException {
    +message: string
}

class UserAlreadyExistsException {
    +message: string
}

AuthRepository --|> Repository        
DefaultAuthRepository --|> AuthRepository  
Repository --> PDO : usa

DefaultAuthRepository ..> UserNotFoundException : throws
DefaultAuthRepository ..> UserAlreadyExistsException : throws

UserNotFoundException --|> Exception
UserAlreadyExistsException --|> Exception
```

### Service Layer
```mermaid 
classDiagram

class AuthService {
    <<interface>>
    +login(email: string, password: string) Response
    +register(name: string, email: string, password: string) Response
}

class DefaultAuthService {
    -authRepository: AuthRepository
    -passwordValidator: PasswordValidator
    -jwtTokenManager: JwtTokenManager
    +login(email: string, password: string) Response
    +register(name: string, email: string, password: string) Response
}

class PasswordValidator {
    <<interface>>
    +validate(password: string, passwordHash: string) bool
}

class JwtTokenManager {
    <<interface>>
    +encode(data: Object) string
    +decode(token: string) Object
}

class AuthRepository {
    <<interface>>
}
DefaultAuthService ..|> AuthService
DefaultAuthService --> PasswordValidator
DefaultAuthService --> AuthRepository
DefaultAuthService --> JwtTokenManager
AuthService --> Response
```

### Controller Layer
```mermaid
classDiagram

class Controller {
    <<Controller>>
}

class AuthController {
    -authService: AuthService
    +resolveAction(action: string) Response
}

class AuthService{
    <<interface>>
}
AuthController --|> Controller
AuthController --> AuthService
Controller --> Response
```

## Diagrammi di Sequenza

### Flusso Login (Backend)

```mermaid
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant router as Router
    participant controller as :AuthController
    participant service as :AuthService
    participant repository as :AuthRepository
    participant db as Database

    frontend ->>+ router: POST /auth/login
    router ->>+ controller: resolveAction("login")
    controller ->>+ service: login(email, password)
    service ->>+ repository: findByEmail(email)
    repository ->>+ db: query
    db -->>- repository: user data
    repository -->>- service: User
    
    alt success
        service ->> service: validate(password, user.password)
        service ->> service: encode(user)
        service -->>- controller: Response(200, success, {token, user})
        controller -->>- router: Response(200, success, {token, user})
    else user not found
        repository -->> service: throw UserNotFoundException
        service -->> controller: Response(404, false, "User not found")
        controller -->> router: Response(404, false, "User not found")
    else invalid password
        service -->> controller: Response(401, false, "Invalid credentials")
        controller -->> router: Response(401, false, "Invalid credentials")
    end

    router -->>- frontend: HTTP Response
```

### Flusso Registrazione (Backend)

```mermaid
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant router as Router
    participant controller as :AuthController
    participant service as :AuthService
    participant repository as :AuthRepository
    participant db as Database

    frontend ->>+ router: POST /auth/register
    router ->>+ controller: resolveAction("register")
    controller ->>+ service: register(name, email, password)
    service ->>+ repository: findByEmail(email)
    repository ->>+ db: query
    db -->>- repository: null (if new)
    
    alt user exists
        repository -->> service: throws UserAlreadyExistsException
        service -->> controller: Response(409, false, "Email already exists")
        controller -->> router: Response
    else new user
        service ->> service: hash(password)
        service ->>+ repository: register(name, email, hash)
        repository ->>+ db: insert
        db -->>- repository: user data
        repository -->>- service: User
        service ->> service: encode(user)
        service -->>- controller: Response(201, success, {token, user})
        controller -->>- router: Response
    end

    router -->>- frontend: HTTP Response
```
