# Modulo Profilo - Backend

## Panoramica

Questo modulo gestisce il profilo utente, permettendo agli utenti di visualizzare i propri dati personali e di modificarli.

## Diagramma delle Classi

```mermaid
classDiagram
    class ProfileController {
        -service: ProfileService
        -authMiddleware: HttpSecurity
        #registerCommands()
    }

    class GetProfileCommand {
        -service: ProfileService
        +execute(body, query)
    }

    class UpdateProfileCommand {
        -service: ProfileService
        +execute(body, query)
    }

    class ProfileService {
        <<interface>>
        +getProfile(userId) array
        +updateProfile(userId, data) array
        +changePassword(userId, currentPassword, newPassword) array
    }

    class StandardProfileService {
        -userRepository: AuthRepository
        -passwordManager: PasswordManager
        +getProfile(userId)
        +updateProfile(userId, data)
        +changePassword(userId, currentPassword, newPassword)
    }

    class AuthRepository {
        <<interface>>
        +getUserById(id) User
        +updateUser(id, data) bool
        +getByEmail(email) User
    }

    class PostgreAuthRepository {
        -db: PDO
        +getUserById(id)
        +updateUser(id, data)
        +getByEmail(email)
    }

    class User {
        +id: int
        +name: string
        +email: string
        +role: string
        +createdAt: DateTime
    }

    class UserProfile {
        +id: int
        +name: string
        +email: string
        +role: string
        +createdAt: DateTime
        +updatedAt: DateTime
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

    ProfileController --> GetProfileCommand : crea
    ProfileController --> UpdateProfileCommand : crea
    GetProfileCommand --> ProfileService
    UpdateProfileCommand --> ProfileService
    StandardProfileService ..|> ProfileService
    StandardProfileService --> AuthRepository
    StandardProfileService --> PasswordManager
    PostgreAuthRepository ..|> AuthRepository
    ProfileController --> HttpSecurity
    AuthMiddleware ..|> HttpSecurity
```

## Flusso di Visualizzazione Profilo

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as ProfileController
    participant Command as GetProfileCommand
    participant Service as StandardProfileService
    participant Repo as PostgreAuthRepository
    participant DB as PostgreSQL

    Client ->> Router: GET /profile
    Router ->> Controller: resolveAction("")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai userId dal token
    
    Command ->> Service: getProfile(userId)
    
    Service ->> Repo: getUserById(userId)
    Repo ->> DB: SELECT * FROM users WHERE id = ?
    DB -->> Repo: user row
    Repo -->> Service: {id, name, email, role, createdAt, updatedAt}
    
    Service -->> Command: {id, name, email, role, createdAt, updatedAt}
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {profile}
```

## Flusso di Aggiornamento Profilo

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as ProfileController
    participant Command as UpdateProfileCommand
    participant Service as StandardProfileService
    participant Repo as PostgreAuthRepository
    participant DB as PostgreSQL

    Client ->> Router: PUT /profile {name, email, password, currentPassword}
    Router ->> Controller: resolveAction("")
    
    Note over Controller: Autenticazione richiesta
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai userId dal token e dati dal body
    
    Command ->> Service: updateProfile(userId, data)
    
    alt Modifica password richiesta
        Note over Service: Valida password corrente
        
        Service ->> Repo: getUserById(userId)
        Repo ->> DB: SELECT * FROM users WHERE id = ?
        DB -->> Repo: user row with hashed password
        Repo -->> Service: {id, name, email, password}
        
        Service ->> Service: validate(currentPassword, hashedPassword)
        
        alt Password non valida
            Service -->> Command: Error("Password corrente non valida")
            Command -->> Controller: Response(400, error, message)
            Controller -->> Router: Response
            Router -->> Client: HTTP 400 {error}
        else Password valida
            Note over Service: Hash nuova password
            
            Service ->> Service: hash(newPassword)
            Service -->> Service: hashedPassword
        end
    end
    
    alt Modifica email richiesta
        Note over Service: Verifica email non già in uso
        
        Service ->> Repo: getByEmail(newEmail)
        Repo ->> DB: SELECT * FROM users WHERE email = ?
        DB -->> Repo: user row or null
        Repo -->> Service: user or null
        
        alt Email già in uso da altro utente
            Service -->> Command: Error("Email già in uso")
            Command -->> Controller: Response(400, error, message)
            Controller -->> Router: Response
            Router -->> Client: HTTP 400 {error}
        end
    end
    
    Service ->> Repo: updateUser(userId, {name, email, password})
    Repo ->> DB: UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?
    DB -->> Repo: success
    Repo -->> Service: true
    
    Service -->> Command: {id, name, email, updatedAt}
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {profile}
```
