# Repository (Interfacce)

Questo documento descrive le interfacce per l'**accesso ai dati** nel backend. Non esiste una classe base astratta; ogni repository implementa direttamente la propria interfaccia.

## Descrizione

Ogni interfaccia `Repository` definisce i metodi per l'accesso a una specifica entità del dominio. Le implementazioni concrete gestiscono la logica di persistenza (tipicamente PostgreSQL).

## Interfacce

### AuthRepository

```mermaid
classDiagram
class AuthRepository {
    <<interface>>
    +getUserById(id: int) User
    +login(email: string, password: string) User
    +register(name: string, email: string, password: string, role: Role) User
}
```

### BookingRepository

```mermaid
classDiagram
class BookingRepository {
    <<interface>>
    +getBooking(resourceId: int, date: string, slot: string)
    +insertBooking(userId: int, resourceId: int, date: string, slot: string) int
}
```

### FieldsRepository

```mermaid
classDiagram
class FieldsRepository {
    <<interface>>
    +getAll() array
    +getResourceById(id: int) array
}
```

### CoursesRepository

```mermaid
classDiagram
class CoursesRepository {
    <<interface>>
    +getAll() array
    +getResourceById(id: int) array
}
```

### ResourcesRepository

```mermaid
classDiagram
class ResourcesRepository {
    <<interface>>
    +getAll() array
    +getResourceById(id: int) array
}
```

## Implementazioni

Le seguenti classi implementano le interfacce repository:

```mermaid
classDiagram
class AuthRepository {
    <<interface>>
}

class BookingRepository {
    <<interface>>
}

class FieldsRepository {
    <<interface>>
}

class CoursesRepository {
    <<interface>>
}

class PostgreAuthRepository {
    -db: PDO
}

class FieldBookingRepository {
    -db: PDO
}

class PostgreFieldsRepository {
    -db: PDO
}

class PostgreCoursesRepository {
    -db: PDO
}

AuthRepository <|.. PostgreAuthRepository
BookingRepository <|.. FieldBookingRepository
FieldsRepository <|.. PostgreFieldsRepository
CoursesRepository <|.. PostgreCoursesRepository
PostgreFieldsRepository ..|> ResourcesRepository
PostgreCoursesRepository ..|> ResourcesRepository
```

## Dipendenze

```mermaid
classDiagram
class Repository {
    <<interface>>
}

class PDO {
}

class Service {
    -repository: Repository
}

class CustomException {
}

Repository --> PDO : utilizza
Service --> Repository : utilizza
Repository --> CustomException : lancia
```

### Relazioni

- **Utilizza**: `PDO` - per la connessione e query al database PostgreSQL
- **Utilizzata da**: Service layer (AuthService, BookingService, ecc)
- **Implementata da**: Classi concrete (PostgreAuthRepository, ecc)
- **Lancia**: `CustomException` - per errori di accesso ai dati ...  
