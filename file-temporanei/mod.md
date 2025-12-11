# Analisi Casi d'uso e Diagrammi di Sequenza

Approfondimento dei diagrammi di sequenza per i casi d'uso dal UC02 al UC13, includendo i flussi principali e le varianti alternative (errori, eccezioni).

## UC02 - Gestione Profilo Utente

### Flusso Principale - Visualizzazione e Modifica Profilo

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as UserService
    participant DB as Database

    Utente ->> GUI: Accede al profilo
    GUI ->> API: GET /users/me
    API ->> Service: getProfile(userId)
    Service ->> DB: findById(userId)
    DB -->> Service: Dati utente
    Service -->> API: Dati utente
    API -->> GUI: 200 OK + JSON Dati
    GUI -->> Utente: Mostra profilo

    Utente ->> GUI: Modifica dati e conferma
    GUI ->> API: PUT /users/me (nuoviDati)
    API ->> API: validate(nuoviDati)
    API ->> Service: updateProfile(userId, nuoviDati)
    Service ->> DB: update(userId, nuoviDati)
    DB -->> Service: Conferma
    Service -->> API: Profilo aggiornato
    API -->> GUI: 200 OK
    GUI -->> Utente: Mostra conferma
```

### Flusso Alternativo - Errore Validazione Dati (ERR01)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as UserService

    Utente ->> GUI: Inserisce dati invalidi (es. email errata)
    GUI ->> API: PUT /users/me (datiInvalidi)
    API ->> API: validate(datiInvalidi)
    API -->> GUI: 400 Bad Request (Validation Error)
    GUI -->> Utente: Mostra errore validazione
```

## UC03 - Visualizzazione Disponibilità Campi

### Flusso Principale - Ricerca Slot

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as BookingService
    participant DB as Database

    Utente ->> GUI: Seleziona sport e data
    GUI ->> API: GET /slots/available?sport=tennis&date=...
    API ->> Service: getAvailableSlots(sport, date)
    Service ->> DB: findOccupiedSlots(sport, date)
    DB -->> Service: Lista slot occupati
    Service ->> Service: calcolaSlotLiberi()
    Service -->> API: Lista slot liberi
    API -->> GUI: 200 OK + Lista JSON
    GUI -->> Utente: Mostra calendario
```

### Flusso Alternativo - Nessuno Slot Disponibile

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as BookingService

    Utente ->> GUI: Seleziona sport e data
    GUI ->> API: GET /slots/available?sport=...
    API ->> Service: getAvailableSlots(...)
    Service -->> API: Lista vuota []
    API -->> GUI: 200 OK + []
    GUI -->> Utente: Mostra messaggio "Nessuna disponibilità"
```

## UC04 - Prenotazione Campo Sportivo

### Flusso Principale - Prenotazione con Successo

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as BookingService
    participant Pay as PaymentGateway
    participant DB as Database

    Utente ->> GUI: Seleziona slot e prenota
    GUI ->> API: POST /bookings (slotId, metodoPagamento)
    API ->> Service: createBooking(userId, slotId)
    Service ->> DB: checkAvailability(slotId)
    DB -->> Service: Disponibile
    Service ->> DB: lockSlot(slotId)
    Service ->> Pay: processPayment(importo)
    Pay -->> Service: Successo
    Service ->> DB: saveBooking(details)
    DB -->> Service: OK
    Service -->> API: Booking ID
    API -->> GUI: 201 Created
    GUI -->> Utente: Conferma prenotazione
```

### Flusso Alternativo - Slot Non Più Disponibile (ERR01)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as BookingService
    participant DB as Database

    Utente ->> GUI: Seleziona slot
    GUI ->> API: POST /bookings (slotId)
    API ->> Service: createBooking(slotId)
    Service ->> DB: checkAvailability(slotId)
    DB -->> Service: Non disponibile (Occupato)
    Service -->> API: Exception: SlotOccupato
    API -->> GUI: 409 Conflict
    GUI -->> Utente: Errore: Slot già prenotato da altri
```

### Flusso Alternativo - Pagamento Fallito (ERR02)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as BookingService
    participant Pay as PaymentGateway
    participant DB as Database

    Utente ->> GUI: Seleziona slot e paga
    GUI ->> API: POST /bookings
    API ->> Service: createBooking()
    Service ->> DB: lockSlot(slotId)
    Service ->> Pay: processPayment(importo)
    Pay -->> Service: Fallimento / Fondi insufficienti
    Service ->> DB: unlockSlot(slotId)
    Service -->> API: Exception: PaymentFailed
    API -->> GUI: 402 Payment Required
    GUI -->> Utente: Errore pagamento
```

## UC05 - Visualizzazione Corsi

### Flusso Principale - Elenco Corsi

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as CourseService
    participant DB as Database

    Utente ->> GUI: Accede sezione Corsi
    GUI ->> API: GET /courses
    API ->> Service: getAllActiveCourses()
    Service ->> DB: findActiveCourses()
    DB -->> Service: Lista Corsi
    Service -->> API: JSON Lista Corsi
    API -->> GUI: 200 OK
    GUI -->> Utente: Mostra lista corsi
```

## UC06 - Iscrizione a Lezione di un Corso

### Flusso Principale - Iscrizione con Successo

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as CourseService
    participant Pay as PaymentGateway
    participant DB as Database

    Utente ->> GUI: Seleziona lezione e paga
    GUI ->> API: POST /courses/lessons/{id}/enroll
    API ->> Service: enrollStudent(userId, lessonId)
    Service ->> DB: checkAvailability(lessonId)
    DB -->> Service: Posti disponibili
    Service ->> Pay: processPayment(prezzo)
    Pay -->> Service: Successo
    Service ->> DB: addStudentToLesson(userId, lessonId)
    Service ->> DB: decrementAvailableSeats(lessonId)
    DB -->> Service: OK
    Service -->> API: Iscrizione confermata
    API -->> GUI: 200 OK
    GUI -->> Utente: Mostra conferma iscrizione
```

### Flusso Alternativo - Posti Esauriti (ERR01)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as CourseService
    participant DB as Database

    Utente ->> GUI: Seleziona lezione
    GUI ->> API: POST /courses/lessons/{id}/enroll
    API ->> Service: enrollStudent
    Service ->> DB: checkAvailability(lessonId)
    DB -->> Service: Posti esauriti (0)
    Service -->> API: Exception: CourseFull
    API -->> GUI: 409 Conflict
    GUI -->> Utente: Errore: Corso completo
```

## UC07 - Cancellare Prenotazione

### Flusso Principale - Cancellazione Valida

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as BookingService
    participant DB as Database

    Utente ->> GUI: Seleziona prenotazione e cancella
    GUI ->> API: DELETE /bookings/{id}
    API ->> Service: cancelBooking(userId, bookingId)
    Service ->> DB: findBooking(bookingId)
    DB -->> Service: Dettagli prenotazione
    Service ->> Service: checkTimeConstraint(now, bookingDate)
    Note right of Service: Deve mancare > 24h
    Service ->> DB: updateStatus(bookingId, CANCELLED)
    Service ->> DB: freeSlot(slotId)
    DB -->> Service: OK
    Service -->> API: Cancellazione avvenuta
    API -->> GUI: 200 OK
    GUI -->> Utente: Conferma cancellazione
```

### Flusso Alternativo - Cancellazione Tardiva (ERR01)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as BookingService
    participant DB as Database

    Utente ->> GUI: Tenta cancellazione
    GUI ->> API: DELETE /bookings/{id}
    API ->> Service: cancelBooking
    Service ->> DB: findBooking
    Service ->> Service: checkTimeConstraint
    Note right of Service: Manca < 24h
    Service -->> API: Exception: LateCancellation
    API -->> GUI: 400 Bad Request
    GUI -->> Utente: Errore: Impossibile cancellare (meno di 24h)
```

## UC08 - Effettuare Pagamento Singolo

### Flusso Principale - Pagamento Diretto

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as CheckOutController
    participant Service as PaymentService
    participant Gateway as Stripe/PayPal

    Utente ->> GUI: Conferma ordine
    GUI ->> API: POST /payments/checkout
    API ->> Service: initPayment(orderId, amount)
    Service ->> Gateway: createPaymentIntent()
    Gateway -->> Service: paymentToken
    Service -->> API: paymentToken
    API -->> GUI: Token per frontend
    GUI ->> Gateway: Conferma pagamento (lato client)
    Gateway -->> GUI: Successo
    GUI ->> API: POST /payments/verify
    API ->> Service: verifyPayment(token)
    Service ->> Gateway: checkStatus(token)
    Gateway -->> Service: PAID
    Service -->> API: Pagamento OK
    API -->> GUI: 200 OK Conferma
```

### Flusso Alternativo - Errore Fondi (ERR01)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant Gateway as Stripe/PayPal

    Utente ->> GUI: Conferma ordine
    GUI ->> Gateway: Tenta pagamento
    Gateway -->> GUI: Errore: Carta rifiutata
    GUI -->> Utente: Messaggio errore pagamento
```

## UC09 - Acquistare Abbonamento

### Flusso Principale - Acquisto Nuovo Abbonamento

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as SubscriptionService
    participant Pay as PaymentGateway
    participant DB as Database

    Utente ->> GUI: Seleziona piano (mensile/annuale)
    GUI ->> API: POST /subscriptions/purchase (planId)
    API ->> Service: buySubscription(userId, planId)
    Service ->> DB: findActiveSubscription(userId)
    DB -->> Service: Nessun abbonamento attivo
    Service ->> Pay: processPayment(costo)
    Pay -->> Service: Successo
    Service ->> DB: createSubscription(userId, planId, validUntil)
    DB -->> Service: OK
    Service -->> API: Abbonamento attivato
    API -->> GUI: 200 OK
    GUI -->> Utente: Conferma attivazione
```

### Flusso Alternativo - Abbonamento Già Attivo (ERR01)

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as SubscriptionService
    participant DB as Database

    Utente ->> GUI: Seleziona piano
    GUI ->> API: POST /subscriptions/purchase
    API ->> Service: buySubscription
    Service ->> DB: findActiveSubscription
    DB -->> Service: Abbonamento già presente
    Service -->> API: Exception: AlreadySubscribed
    API -->> GUI: 409 Conflict
    GUI -->> Utente: Errore: Hai già un abbonamento attivo
```

## UC10 - Visualizzare Storico Prenotazioni e Pagamenti

### Flusso Principale - Consultazione Storico

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Service as HistoryService
    participant DB as Database

    Utente ->> GUI: Accede a "I miei ordini"
    GUI ->> API: GET /history
    API ->> Service: getUserHistory(userId)
    Service ->> DB: findBookingsByUserId(userId)
    Service ->> DB: findPaymentsByUserId(userId)
    DB -->> Service: Dati grezzi
    Service ->> Service: aggregaDati()
    Service -->> API: DTO Storico Completo
    API -->> GUI: 200 OK
    GUI -->> Utente: Mostra lista prenotazioni/acquisti
```

## UC11 - Gestire Campi Sportivi (Admin)

### Flusso Principale - Creazione Campo

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant GUI as GUI
    participant API as AdminController
    participant Service as FacilityService
    participant DB as Database

    Admin ->> GUI: Inserisce dati nuovo campo
    GUI ->> API: POST /admin/facilities (dati)
    API ->> API: checkRole(ADMIN)
    API ->> Service: addFacility(dati)
    Service ->> DB: save(facility)
    DB -->> Service: OK (ID generato)
    Service -->> API: Campo creato
    API -->> GUI: 201 Created
    GUI -->> Admin: Conferma creazione
```

### Flusso Alternativo - Eliminazione Campo Occupato (ERR01)

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant GUI as GUI
    participant API as AdminController
    participant Service as FacilityService
    participant DB as Database

    Admin ->> GUI: Elimina campo
    GUI ->> API: DELETE /admin/facilities/{id}
    API ->> Service: deleteFacility(id)
    Service ->> DB: hasFutureBookings(id)
    DB -->> Service: True (Ci sono prenotazioni)
    Service -->> API: Exception: FacilityInUse
    API -->> GUI: 409 Conflict
    GUI -->> Admin: Errore: Impossibile eliminare, ci sono prenotazioni
```

## UC12 - Gestire Corsi e Lezioni (Admin)

### Flusso Principale - Aggiunta Lezione a Corso

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant GUI as GUI
    participant API as AdminController
    participant Service as CourseService
    participant DB as Database

    Admin ->> GUI: Aggiunge lezione a Corso X
    GUI ->> API: POST /admin/courses/{id}/lessons (data, posti)
    API ->> Service: addLesson(courseId, data)
    Service ->> DB: saveLesson(...)
    DB -->> Service: OK
    Service -->> API: Lezione creata
    API -->> GUI: 201 Created
    GUI -->> Admin: Aggiorna lista lezioni
```

## UC13 - Gestire Tariffe e Abbonamenti (Admin)

### Flusso Principale - Aggiornamento Prezzo

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant GUI as GUI
    participant API as AdminController
    participant Service as PricingService
    participant DB as Database

    Admin ->> GUI: Modifica prezzo orario Tennis
    GUI ->> API: PATCH /admin/tariffs/tennis (newPrice)
    API ->> Service: updateTariff("tennis", newPrice)
    Service ->> DB: updatePrice(...)
    DB -->> Service: OK
    Service -->> API: Prezzo aggiornato
    API -->> GUI: 200 OK
    GUI -->> Admin: Conferma modifica
```
