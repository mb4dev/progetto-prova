# Diagrammi di Sequenza

## UC01 - Registrazione / Autenticazione

### Flusso principale - Autenticazione

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Auth as Auth Service
    participant DB as Database

    Utente ->> GUI: Inserisce email e password
    GUI ->> API: POST /auth/login (email, password)
    API ->> API: verificaBody()
    API ->> Auth: autenticaUtente(email, password)
    Auth ->> DB: getUtenteByEmail(email)
    DB -->> Auth: Dati utente + password cifrata

    Auth ->> Auth: verificaPassword(password)
    Auth ->> Auth: generaToken()
    Auth -->> API: token
    API -->> GUI: 200 OK + token
    GUI -->> Utente: Mostra home page
```

### Flusso secondario - Registrazione

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Auth as Auth Service
    participant DB as Database

    Utente ->> GUI: Inserisce dati registrazione
    GUI ->> API: POST auth/register (dati utente)
    API ->> API: verificaBody()
    API ->> Auth: registraUtente(dati utente)
    Auth ->> DB: getUtenteByEmail(email)
    DB -->> Auth:  Nessun utente trovato
    Auth ->> Auth: encryptPassword()
    Auth ->> DB: salvaUtente(utente)
    DB -->> Auth: Conferma salvataggio
    Auth ->> Auth: generaToken()
    Auth -->> API: token
    API -->> GUI: 201 Created + token
    GUI -->> Utente: Mostra home page
```

## UC02 - Visualizzare e gestire profilo utente

### Flusso principale - Visualizzazione e Modifica Profilo

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant UserService as User Service
    participant DB as Database

    %% Visualizzazione
    Utente ->> GUI: Accede al profilo
    GUI ->> API: GET /user/profile
    API ->> UserService: getProfile(userId)
    UserService ->> DB: getUserById(userId)
    DB -->> UserService: Dati utente
    UserService -->> API: Dati utente
    API -->> GUI: Mostra dati profilo

    %% Modifica
    Utente ->> GUI: Modifica dati e conferma
    GUI ->> API: PUT /user/profile (nuovi dati)
    API ->> API: verificaBody()
    API ->> UserService: updateProfile(userId, nuoviDati)
    UserService ->> DB: updateUser(userId, nuoviDati)
    DB -->> UserService: Conferma aggiornamento
    UserService -->> API: Profilo aggiornato
    API -->> GUI: 200 OK
    GUI -->> Utente: Mostra conferma
```

## UC03 - Visualizzare disponibilità campi

### Flusso principale - Ricerca Disponibilità

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant BookingService as Booking Service
    participant DB as Database

    Utente ->> GUI: Seleziona sport e data
    GUI ->> API: GET /bookings/booked
    API ->> BookingService: getBookedSlots(sport, date)
    BookingService ->> DB: getBookedSlots(sport, date)
    DB -->> BookingService: Lista slot occupati
    BookingService -->> API: Lista slot occupati
    API -->> GUI: 200 Ok + Lista slot occupati
    GUI ->> GUI: Render calendario disponibilità
    GUI -->> Utente: Mostrato calendario
```

## UC04 - Prenotare campo sportivo

### Flusso principale - Prenotazione con Pagamento

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant BookingService as Booking Service
    participant DB as Database

    Utente ->> GUI: Seleziona slot disponibile
    GUI ->> API: POST /bookings/reserve
    API ->> BookingService: createReservation(sport, date)
    BookingService ->> DB: checkAvailability(sport, date)
    DB -->> BookingService: Slot libero
    BookingService ->> DB: createBooking(status="PENDING")
    DB -->> BookingService: bookingID
    BookingService -->> API: bookingID
    API -->> GUI: 200 Ok + bookingID
    Utente ->> GUI: Pagamento  
    GUI ->> API: /bookings/confirmBooking(bookingId)   
    API ->> BookingService: confirmBooking(bookingId)
    BookingService ->> DB: updateBookingStatus(bookingId, "CONFIRMED")
    DB -->> BookingService: Aggiornato
    BookingService -->> API: OK
    API -->> GUI: 200 OK Prenotazione Confermata
    GUI -->> Utente: Mostra conferma prenotazione
```

## UC05 - Visualizzare corsi

### Flusso principale - Elenco Corsi

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant CourseService as Course Service
    participant DB as Database

    Utente ->> GUI: Accede sezione Corsi
    GUI ->> API: GET /courses
    API ->> CourseService: getAllCourses()
    CourseService ->> DB: getAllCourses()
    DB -->> CourseService: Lista corsi
    CourseService -->> API: Lista corsi
    API -->> GUI: Mostra elenco corsi
```

## UC06 - Iscriversi a una lezione di un corso

### Flusso principale - Iscrizione Lezione

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant CourseService as Course Service
    participant DB as Database

    Utente ->> GUI: Seleziona lezione e clicca "Iscriviti"
    GUI ->> API: POST /courses/enroll/{lessonId}
    API ->> CourseService: enrollUser(lessonId, userId)
    CourseService ->> DB: checkLessonAvailability(lessonId)
    DB -->> CourseService: Posti disponibili
    
    CourseService ->> DB: addParticipant(lessonId, userId)
    DB -->> CourseService: OK
    CourseService -->> API: Iscrizione confermata
    API -->> GUI: 200 OK
    GUI -->> Utente: Mostra conferma iscrizione
```

## UC07 - Cancellare prenotazione

### Flusso principale - Cancellazione valida

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant BookingService as Booking Service
    participant DB as Database

    Utente ->> GUI: Seleziona storico 
    GUI -->> Utente: Mostra storico
    Utente ->> GUI: Clicca "Cancella" sula prenotazione
    GUI ->> API: DELETE /bookings/cancel
    API ->> BookingService: cancelBooking(bookingId)
    BookingService ->> DB: getBooking(bookingId)
    DB -->> BookingService: Dettagli prenotazione
    
    BookingService ->> BookingService: verificaScadenza(24h)
    
    BookingService ->> DB: updateStatus(bookingId, "CANCELLED")
    DB -->> BookingService: OK
    
    BookingService -->> API: Cancellazione effettuata
    API -->> GUI: 200 OK
    GUI -->> Utente: Conferma cancellazione
```

## UC08 - Effettuare pagamento singolo

### Flusso principale - Processo di Pagamento

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant PaymentService as Payment Service
    participant DB as Database

    GUI -->> Utente: Mostra riepilogo ordine
    Utente ->> GUI: Inserisce dati pagamento
    Utente ->> GUI: Conferma pagamento
    GUI ->> API: POST /payments/checkout (orderData)
    API ->> PaymentService: createPayment(orderData)

    PaymentService ->> DB: savePayment(paymentData)
    DB -->> PaymentService: Salvataggio riuscito 
    PaymentService -->> API: Pagamento riuscito
    API -->> GUI: 200 Ok 
    GUI -->> Utente: Mostrato messaggio di conferma
```

## UC09 - Acquistare abbonamento

### Flusso principale - Acquisto Abbonamento

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant SubscriptionService as Sub Service
    participant PaymentService as Payment Service
    participant DB as Database

    Utente ->> GUI: Seleziona "Abbonati"
    GUI -->> Utente: Mostra la pagina abbonamenti
    Utente ->> GUI: Selezione abbonamento
    GUI ->> API: POST /subscriptions/purchase (planId)
    API ->> SubscriptionService: purchase(planId, userId)
    
    SubscriptionService ->> PaymentService: createPayment(orderData)
    PaymentService -->> SubscriptionService: Pagamento OK
    
    SubscriptionService ->> DB: createSubscription(userId, planId, startDate, endDate)
    DB -->> SubscriptionService: SubcriptionID
    
    SubscriptionService -->> API: Abbonamento Attivato
    API -->> GUI: 200 Ok Conferma abbonamento
    GUI -->> Utente: Mostra nuovo stato abbonamento
```

## UC10 - Visualizzare storico prenotazioni e pagamenti

### Flusso principale - Visualizzazione Storico

```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant HistoryService as History Service
    participant DB as Database

    Utente ->> GUI: Accede allo storico
    GUI ->> API: GET /user/history
    API ->> HistoryService: getUserHistory(userId)
    HistoryService ->> DB: getBookingsByUserId(userId)
    HistoryService ->> DB: getPaymentsByUserId(userId)
    DB -->> HistoryService: Data
    HistoryService ->> HistoryService: processData()
    HistoryService -->> API: Lista storico
    API -->> GUI: 200 Ok + Lista cronologica
    GUI -->> Utente: Visualizza storico
```

## UC11 - Gestire campi sportivi (Admin)

### Flusso principale - Creazione Campo

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant GUI as GUI
    participant API as RestController
    participant AdminService as Admin Service
    participant DB as Database

    Admin ->> GUI: Accede alla sezione gestione
    Admin ->> GUI: Inserisce dati nuovo campo
    GUI ->> API: PUT /admin/fields (datiCampo)
    API ->> API: checkRole(ADMIN)
    API ->> AdminService: createField(datiCampo) o updateField(datiCampo)
    AdminService ->> DB: saveField(datiCampo)
    DB -->> AdminService: Field ID
    AdminService -->> API: Campo creato
    API -->> GUI: 201 Created
    GUI -->> Admin: Conferma creazione
```

## UC12 - Gestire corsi e lezioni (Admin)

### Flusso principale - Aggiunta Lezione

```mermaid

```

## UC13 - Gestire tariffe e abbonamenti (Admin)

### Flusso principale - Aggiornamento Tariffa

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant GUI as AdminPanel
    participant API as RestController
    participant TariffService as Tariff Service
    participant DB as Database

    Admin ->> AdminPanel: Modifica prezzo tariffa
    AdminPanel ->> API: PUT /admin/tariffs/{tariffId} (newPrice)
    API ->> API: checkRole(ADMIN)
    API ->> TariffService: updateTariff(tariffId, newPrice)
    TariffService ->> DB: updatePrice(tariffId, newPrice)
    DB -->> TariffService: OK
    TariffService -->> API: Tariffa aggiornata
    API -->> AdminPanel: 200 OK
    AdminPanel -->> Admin: Conferma modifica
```
