# BookingService Interface

Questa è l'interfaccia utilizzata nel progetto per la **logica di business relativa alle prenotazioni** nel backend.

## Descrizione

L'interfaccia `BookingService` definisce il contratto per i servizi che gestiscono la logica di business delle prenotazioni, inclusa la creazione, conferma e cancellazione.

## Metodi

```mermaid
classDiagram
class BookingService {
    <<interface>>
    +createBooking(userId: int, fieldId: int, startTime: string, endTime: string) Response
    +confirmBooking(bookingId: int) Response
    +cancelBooking(bookingId: int) Response
}
```

## Responsabilità

- Creare nuove prenotazioni con validazione
- Confermare prenotazioni esistenti
- Cancellare prenotazioni
- Applicare regole di business (es. validazione orari)
- Gestire transazioni e conflitti

## Implementazioni

Le seguenti classi implementano questa interfaccia:

```mermaid
classDiagram
class BookingService {
    <<interface>>
    +createBooking(userId: int, fieldId: int, startTime: string, endTime: string) Response
    +confirmBooking(bookingId: int) Response
    +cancelBooking(bookingId: int) Response
}

class DefaultBookingService {
    -bookingRepository: BookingRepository
    -fieldRepository: FieldRepository
    +createBooking(userId: int, fieldId: int, startTime: string, endTime: string) Response
    +confirmBooking(bookingId: int) Response
    +cancelBooking(bookingId: int) Response
    -validateBookingTime(startTime: string, endTime: string) bool
    -calculateAmount(fieldId: int, startTime: string, endTime: string) float
}

DefaultBookingService ..|> BookingService
DefaultBookingService --> BookingRepository
DefaultBookingService --> FieldRepository
```

### DefaultBookingService
- **Scopo**: Implementazione standard del servizio per prenotazioni
- **Utilizzo**: UC04 - Prenotazione campo sportivo
- **Caratteristiche**: Validazione orari, calcolo importo, gestione stati prenotazione

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa interfaccia:

```mermaid
classDiagram
class BookingService {
    <<interface>>
    +createBooking(userId: int, fieldId: int, startTime: string, endTime: string) Response
    +confirmBooking(bookingId: int) Response
    +cancelBooking(bookingId: int) Response
}

class BookingRepository {
    <<abstract>>
    +create(booking: Booking) Booking
    +checkAvailability() bool
}

class FieldRepository {
    <<abstract>>
    +getById(id: int) Field
}

class Response {
    +code: int
    +success: bool
    +data: mixed
}

class BookingController {
    -bookingService: BookingService
}

class DefaultBookingService {
    +createBooking() Response
}

BookingService --> BookingRepository : utilizza
BookingService --> FieldRepository : utilizza
BookingService --> Response : restituisce
BookingController --> BookingService : utilizza
DefaultBookingService ..|> BookingService : implementa
```

### Relazioni
- **Utilizza**: `BookingRepository` - per gestione prenotazioni
- **Utilizza**: `FieldRepository` - per validazione campi e calcolo prezzi
- **Restituisce**: `Response` - risposta formattata
- **Utilizzata da**: `BookingController`
- **Implementata da**: `DefaultBookingService`
