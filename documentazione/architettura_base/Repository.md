# Repository (Classe Astratta)

Questa è la classe astratta utilizzata nel progetto per l'**accesso ai dati** nel backend.

## Descrizione

La classe astratta `Repository` definisce la struttura base per l'accesso al database. Fornisce metodi comuni per l'esecuzione di query SQL e gestisce la connessione al database.

## Struttura

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
    #executeQuery(sql: string, params: array) array
}
```

## Responsabilità

- Gestire la connessione al database (PDO)
- Fornire metodi helper per eseguire query SQL

## Pattern

Implementa il **Repository Pattern** per separare la logica di accesso ai dati dalla logica di business.

## Implementazioni

Le seguenti classi estendono questa classe astratta:

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
    #executeQuery(sql: string, params: array) array
}

class AuthRepository {
    <<abstract>>
    +login(email: string, password: string) User
    +register(name: string, email: string, password: string) User
}

class UserRepository {
    <<abstract>>
    +getById(id: int) User
    +update(user: User) bool
}

class FieldRepository {
    <<abstract>>
    +getAll() Field[]
    +getById(id: int) Field
    +getByType(sport: string) Field[]
}

class BookingRepository {
    <<abstract>>
    +create(booking: Booking) Booking
    +getById(id: int) Booking
    +checkAvailability(fieldId: int, startTime: string, endTime: string) bool
    +getOccupiedSlotsByWeek(fieldId: int, startDate: string, endDate: string) Slot[]
    +updateStatus(id: int, status: string) bool
}

AuthRepository --|> Repository
UserRepository --|> Repository
FieldRepository --|> Repository
BookingRepository --|> Repository
```

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
    #executeQuery(sql: string, params: array) array
}

class PDO {
    <<PHP>>
    +prepare(sql: string)
    +execute(params: array)
}

class Service {
    -repository: Repository
}

class AuthRepository {
    <<abstract>>
}

class DefaultAuthRepository {
    +login() User
}

Repository --> PDO : utilizza
Service --> Repository : utilizza
AuthRepository --|> Repository : estende
DefaultAuthRepository --|> AuthRepository : estende
```

### Relazioni

- **Utilizza**: `PDO` - per la connessione e query al database
- **Utilizzata da**: Service layer (AuthService, UserService, ecc)
- **Estesa da**: `AuthRepository`, `UserRepository`, ...  
