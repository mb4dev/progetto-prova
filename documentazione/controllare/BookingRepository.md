# BookingRepository (Classe Astratta)

Questa è la classe astratta utilizzata nel progetto per l'**accesso ai dati delle prenotazioni** nel database.

## Descrizione

La classe astratta `BookingRepository` estende `Repository` e definisce i metodi specifici per gestire le operazioni sulle prenotazioni dei campi sportivi.

## Struttura

```mermaid
classDiagram
class BookingRepository {
    <<abstract>>
    +create(booking: Booking) Booking
    +getById(id: int) Booking
    +checkAvailability(fieldId: int, startTime: string, endTime: string) bool
    +getOccupiedSlotsByWeek(fieldId: int, startDate: string, endDate: string) Slot[]
    +updateStatus(id: int, status: string) bool
}
```

## Responsabilità

- Creare nuove prenotazioni
- Recuperare prenotazioni per ID
- Verificare la disponibilità di un campo in un determinato orario
- Recuperare gli slot occupati in una settimana
- Aggiornare lo stato di una prenotazione

## Implementazioni

Le seguenti classi estendono questa classe astratta:

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
    #executeQuery(sql: string, params: array) array
}

class BookingRepository {
    <<abstract>>
    +create(booking: Booking) Booking
    +getById(id: int) Booking
    +checkAvailability(fieldId: int, startTime: string, endTime: string) bool
    +getOccupiedSlotsByWeek(fieldId: int, startDate: string, endDate: string) Slot[]
    +updateStatus(id: int, status: string) bool
}

class DefaultBookingRepository {
    +create(booking: Booking) Booking
    +getById(id: int) Booking
    +checkAvailability(fieldId: int, startTime: string, endTime: string) bool
    +getOccupiedSlotsByWeek(fieldId: int, startDate: string, endDate: string) Slot[]
    +updateStatus(id: int, status: string) bool
    -mapRowToBooking(row: array) Booking
    -mapRowToSlot(row: array) Slot
}

Repository <|-- BookingRepository
BookingRepository <|-- DefaultBookingRepository
```

### DefaultBookingRepository
- **Scopo**: Implementazione standard del repository per prenotazioni
- **Utilizzo**: Utilizzato in produzione per gestione prenotazioni
- **Caratteristiche**: Transazioni per atomicità, lock pessimistico per evitare race conditions

## Eccezioni

Può lanciare:
- `BookingConflictException`: quando si tenta di prenotare uno slot già occupato
- `BookingNotFoundException`: quando una prenotazione non viene trovata

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
}

class BookingRepository {
    <<abstract>>
    +create(booking: Booking) Booking
    +checkAvailability() bool
    +getOccupiedSlotsByWeek() Slot[]
}

class Booking {
    +id: int
    +fieldId: int
    +userId: int
    +startTime: string
    +endTime: string
}

class Slot {
    +startTime: string
    +endTime: string
    +available: bool
}

class BookingService {
    -bookingRepository: BookingRepository
}

class DefaultBookingRepository {
    +create(booking: Booking) Booking
}

class BookingConflictException {
    +message: string
}

Repository <|-- BookingRepository : estende
BookingRepository --> Booking : gestisce
BookingRepository --> Slot : restituisce
BookingService --> BookingRepository : utilizza
DefaultBookingRepository --|> BookingRepository : estende
DefaultBookingRepository ..> BookingConflictException : throws
```

### Relazioni
- **Estende**: `Repository` - eredita connessione PDO e metodi base
- **Gestisce**: `Booking` - entità prenotazione
- **Restituisce**: `Slot[]` - array di slot temporali
- **Utilizzata da**: `BookingService`, `FieldService` - per logica business
- **Implementata da**: `DefaultBookingRepository`
- **Eccezioni**: Lancia `BookingConflictException`, `BookingNotFoundException`

