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
    +getFields() Field[]
    +getById(fieldId: int) Field
}

class BookingRepository {
    <<abstract>>
	+getOccupiedSlots(fieldId: int, startDate: Date, endDate: Date) Slot[]
    +createBooking(booking: Booking) Booking
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
}

class Service {
    -repository: Repository
}

class IRepository {
    <<abstract>>
}

class ImplRepository {
}

Repository --> PDO : utilizza
Service --> Repository : utilizza
IRepository --|> Repository : estende
ImplRepository --|> IRepository : estende
```

### Relazioni

- **Utilizza**: `PDO` - per la connessione e query al database
- **Utilizzata da**: Service layer (AuthService, UserService, ecc)
- **Estesa da**: `AuthRepository`, `UserRepository`, ...  
