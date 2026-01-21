# Modulo Calendario - Backend

## Casi d'uso Correlati
- **UC03**: Visualizzazione Disponibilità Campi
- **UC04**: Prenotazione Campo Sportivo
- **UC05**: Visualizzazione Corsi
- **UC06**: Iscrizione a una Lezione

Questo modulo gestisce la logica per la disponibilità di campi e corsi e la gestione delle prenotazioni lato server.

## Diagrammi di Sequenza

### Flusso Recupero Disponibilità (Backend - UC03)

```mermaid
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant router as Router
    participant controller as :FieldController
    participant service as :FieldService
    participant repository as :FieldRepository
    participant bookingRepo as :FieldBookingRepository
    participant db as Database

    frontend ->>+ router: POST /fields/occupied
    router ->>+ controller: resolveAction("occupied")
    controller ->>+ service: getOccupiedSlots(fieldId, startDate, endDate)
    service ->>+ bookingRepo: findOverlaps(fieldId, startDate, endDate)
    bookingRepo ->>+ db: query
    db -->>- bookingRepo: occupied slot records
    bookingRepo -->>- service: Slot[]
    
    alt success
        service -->>- controller: Response(200, success, {occupiedSlots: [...]})
        controller -->>- router: Response(200, success, {occupiedSlots: [...]})
    else field not found
        repository -->> service: throw FieldNotFoundException
        service -->> controller: Response(404, false, "Field not found")
        controller -->> router: Response(404, false, "Field not found")
    end
    
    router -->>- frontend: HTTP Response
```

### Flusso Creazione Prenotazione (Backend - UC04)

```mermaid
sequenceDiagram
     autonumber
 
     participant frontend as Frontend
     participant router as Router
     participant controller as :FieldBookingController
     participant service as :FieldBookingService
     participant bookingRepo as :FieldBookingRepository
     participant db as Database
 
     frontend ->>+ router: POST /bookings/create
     router ->>+ controller: resolveAction("create")
     controller ->>+ service: createBooking(userId, fieldId, startTime, endTime)
     service ->>+ bookingRepo: findOverlaps(fieldId, startDate, endDate)
     bookingRepo ->>+ db: query
     db -->>- bookingRepo: occupied slot records
     bookingRepo -->>- service: Slot[]
     service ->> service: checkSlotAvailability(fieldId, startTime, endTime)
 
     alt slot disponibile
         service ->>+ bookingRepo: createBooking(booking)
         bookingRepo ->>+ db: query
         db -->> bookingRepo: booking id
         bookingRepo -->>- service: FieldBooking (status: "pending")
         service -->>- controller: Response(200, success, {bookingId, amount})
         controller -->>- router: Response(200, success, {bookingId, amount})
         
     else slot occupato
         service -->> controller: Response(409, false, "Slot non disponibile")
         controller -->> router: Response(409, false, "Slot non disponibile")
     end
 
     router -->>- frontend: HTTP Response
```

### Flusso Visualizzazione Corsi (Backend - UC05)

```mermaid
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant router as Router
    participant controller as :CourseController
    participant service as :CourseService
    participant courseRepo as :CourseRepository
    participant db as Database

    frontend ->>+ router: GET /courses
    router ->>+ controller: resolveAction()
    controller ->>+ service: getActiveCourses()
    service ->>+ courseRepo: getCourses()
    courseRepo ->>+ db: query
    db -->>- courseRepo: data
    courseRepo -->>- service: Resource[]
    
    alt success
        service -->>- controller: Response(200, success, coursesWithAvailability)
        controller -->>- router: Response(200, success, coursesWithAvailability)
    else db error
        courseRepo -->> service: throw Exception
        service -->> controller: Response(500, false, "db error")
        controller -->> router: Response
    end
    
    router -->>- frontend: HTTP Response
```

### Flusso Iscrizione Lezione (Backend - UC06)

```mermaid
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant router as Router
    participant controller as :CourseBookingController
    participant service as :CourseBookingService
    participant bookingRepo as :CourseBookingRepository
    participant db as Database

    frontend ->>+ router: POST /courses/enroll
    router ->>+ controller: resolveAction("enroll")
    controller ->>+ service: createBooking(userId, lessonId)

    alt    
        service ->>+ bookingRepo: createBooking(lessonId, userId)
        bookingRepo ->>+ db: insert
        db -->> bookingRepo: id
        bookingRepo -->>- service: CourseBooking (status: "pending")
        
        service -->>- controller: Response(200, success, {bookingId, amount})
        controller -->>- router: Response(200, success, {bookingId, amount})
        
    else max capacity
        service -->> controller: Response(409, false, "Posti esauriti")
        controller -->> router: Response(409, false, "Posti esauriti")
    end

    router -->>- frontend: HTTP Response
```
