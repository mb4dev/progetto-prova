# Modulo Calendario - Frontend

## Casi d'uso Correlati
- **UC03**: Visualizzazione Disponibilità Campi (Selezione data/slot)
- **UC04**: Prenotazione Campo Sportivo
- **UC05**: Visualizzazione Corsi (Visualizzazione disponibilità)
- **UC06**: Iscrizione a una Lezione

Questo modulo gestisce la visualizzazione del calendario, la selezione della data e dell'orario, e il riepilogo della prenotazione.

## Componenti

### CalendarPresenter
Coordinatore principale del modulo calendario.
- Inizializza i componenti `DatePicker`, `SlotPicker`, e `ReservationResumeView`.
- Instanzia e inizializza i rispettivi presenter: `DatePickerPresenter`, `SlotPickerPresenter`, `ReservationResumePresenter`.
- Gestisce la logica di avanzamento settimanale (`DATE_INCREMENT_EVENT`).

### CalendarView
Contenitore layout per i tre sotto-moduli:
- Date Picker (selezione giorno)
- Slot Picker (selezione orario)
- Reservation Resume (riepilogo)

### Sotto-componenti
- **DatePicker / DatePickerPresenter**: Gestisce la visualizzazione dei giorni e la selezione della data.
- **SlotPicker / SlotPickerPresenter**: Gestisce la visualizzazione degli slot orari disponibili per la data selezionata.
- **ReservationResumeView / ReservationResumePresenter**: Mostra i dettagli della prenotazione corrente.

## Diagramma delle Classi

```mermaid
classDiagram
    class Presenter {
        <<interface>>
    }

    class CalendarPresenter {
        -#week: Array
        -#initComponents()
        +_handleViewEvents()
    }

    class CalendarView {
        +display(data)
        +template()
    }

    class DatePickerPresenter {
        +init()
    }
    class SlotPickerPresenter {
        +init()
    }
    class ReservationResumePresenter {
        +init()
    }

    CalendarPresenter --|> Presenter
    CalendarPresenter --> CalendarView : manages
    CalendarPresenter --> DatePickerPresenter : creates
    CalendarPresenter --> SlotPickerPresenter : creates
    CalendarPresenter --> ReservationResumePresenter : creates
    
    CalendarView --> DatePicker : contains
    CalendarView --> SlotPicker : contains
    CalendarView --> ReservationResumeView : contains
```

## Diagrammi di Attività

### Visualizzazione Disponibilità (UC03)

```mermaid 
flowchart TD
    Start([Inizio]) --> SelectField[Utente ha selezionato un campo]
    SelectField --> SetDate[Sistema imposta settimana corrente]
    SetDate --> QueryDB[Interroga disponibilità settimana]

    QueryDB --> ShowCalendar[Mostra calendario settimanale]
    ShowCalendar --> MarkOccupied[Frontend colora di rosso slot occupati]

    MarkOccupied --> Choice{Cosa fare?}
    Choice -->|Naviga| NextWeek[Cambia settimana]
    NextWeek --> QueryDB

    Choice -->|Prenota| GoToUC04[Seleziona Slot Libero]
    Choice -->|Esci| End([Fine])
```

### Prenotazione Campo (UC04)

```mermaid 
flowchart TD
    Start([Inizio da UC03]) --> SelectSlot[Utente seleziona slot libero]
    SelectSlot --> CheckAvail{Slot ancora disponibile?}

    CheckAvail -->|No| SlotError[Mostra errore: slot non disponibile]
    SlotError --> RefreshView[Aggiorna vista disponibilità]
    RefreshView --> End1([Fine])

    CheckAvail -->|Sì| CreateBooking[Crea prenotazione]
    CreateBooking --> ShowConfirm[Mostra riepilogo prenotazione]
    ShowConfirm --> UserConfirm{Utente conferma?}

    UserConfirm -->|No| CancelBooking[Annulla prenotazione]
    CancelBooking --> End2([Fine])

    UserConfirm -->|Sì| GoToPayment[Vai a UC08 - Pagamento]
    GoToPayment --> End3([Fine])
```

### Visualizzazione Corsi (UC05)

```mermaid 
flowchart TD
    Start([Inizio]) --> SelectCourses[Utente seleziona sezione Prenotazione corsi]
    SelectCourses --> SelectDate[Utente seleziona la data]
    SelectDate --> LoadCourses[Sistema carica elenco corsi attivi mostrandone la disponibilità]

    LoadCourses --> SelectLesson[Utente seleziona un corso]
    SelectLesson --> CheckSpots{Posti disponibili?}
    
    CheckSpots -->|No| ShowError[Mostra 'Posti esauriti']
    ShowError --> LoadCourses
    
    CheckSpots -->|Sì| Iscrizione[Procedi all'iscrizione]
    Iscrizione --> GoToUC06[Vai a UC06]
    
    GoToUC06 --> End
```

### Iscrizione Lezione (UC06)

```mermaid 
flowchart TD
    Start([Inizio da UC05]) --> SelectLesson[Utente conferma lezione scelta]
    SelectLesson --> CheckAvail{Posti confermati?}

    CheckAvail -->|No| SpotError[Mostra errore: Posti esauriti]
    SpotError --> RedirectUC05[Torna a lista corsi]
    RedirectUC05 --> End1([Fine])

    CheckAvail -->|Sì| CreateEnrollment[Crea iscrizione]
    CreateEnrollment --> ShowRiepilogo[Mostra riepilogo e prezzo]
    ShowRiepilogo --> Confirm{Conferma e paga?}

    Confirm -->|No| CancelEnroll[Annulla operazione]
    CancelEnroll --> End2([Fine])

    Confirm -->|Sì| GoToPayment[Vai a UC08 - Pagamento]
    GoToPayment --> End3([Fine])
```

## Diagrammi di Sequenza

### Flusso Selezione Slot (Frontend - UC03)

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant campiView as CampiView
    participant presenter as CampiPresenter
    participant bus as Observer (eventBus)
    participant api as APIService
    participant backend as Backend
    
    Note over presenter: Calcola startDate e endDate della settimana
    
    presenter ->>+ api: getOccupiedSlots(fieldId, startDate, endDate)
    api ->>+ backend: POST /fields/occupied
    
    alt success
        backend -->>- api: {success: true, occupiedSlots: [...]}
        api -->>- presenter: {success: true, occupiedSlots: [...]}
        presenter ->>+ campiView: display()
        campiView ->> campiView: renderCalendar()
        campiView -->>- user: mostra calendario (slot occupati in rosso)
    else error
        backend -->> api: {success: false, message}
        api -->> presenter: {success: false, message}
        presenter ->> campiView: display(error)
        campiView -->> user: mostra errore
    end
```

### Flusso Prenotazione (Frontend - UC04)

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant campiView as CampiView
    participant bookingView as BookingView
    participant presenter as BookingPresenter
    participant bus as Observer (eventBus)
    participant api as APIService
    participant backend as Backend

    user ->>+ campiView: seleziona slot disponibile
    campiView ->>+ bus: notify(SLOT_SELECT_EVENT, {fieldId, slot})
    bus ->>+ presenter: callback
    
    presenter ->>+ api: createBooking({fieldId, startTime, endTime})
    api ->>+ backend: POST /bookings/create
    
    alt slot disponibile
        backend -->>- api: {success: true, bookingId, amount}
        api -->>- presenter: {success: true, bookingId, amount}
        presenter ->>+ bookingView: display({booking: {bookingId, amount}})
        bookingView -->>- user: mostra riepilogo prenotazione
        
        user ->>+ bookingView: conferma prenotazione
        bookingView ->>+ bus: notify(BOOKING_CONFIRM_EVENT, {bookingId})
        bus ->>+ presenter: callback
        
        Note over presenter: Procede a UC08 per il pagamento
        presenter ->>+ bookingView: navigazione a pagamento
        bookingView -->>- user: vai a pagamento
        
    else slot non disponibile
        backend -->> api: {success: false, error: "Slot non disponibile"}
        api -->> presenter: {success: false, error}
        presenter ->>+ campiView: display({error: "Slot non disponibile"})
        campiView -->>- user: mostra errore e aggiorna disponibilità
    end
```

### Flusso Visualizzazione Corsi (Frontend - UC05)

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant campiView as CorsiView
    participant presenter as CorsiPresenter
    participant api as APIService
    participant backend as Backend

    user ->>+ campiView: accede a sezione Corsi
    campiView ->>+ presenter: init()
    presenter ->>+ api: getCourses()
    api ->>+ backend: GET /courses
    
    alt success
        backend -->>- api: {success: true, courses: [...]}
        api -->>- presenter: {success: true, courses: [...]}
        presenter ->>+ campiView: display(courses)
        campiView -->>- user: mostra lista corsi con disponibilità
    else error
        backend -->> api: {success: false, message}
        api -->> presenter: {success: false, message}
        presenter ->> campiView: display(error)
        campiView -->> user: mostra errore
    end
```

### Flusso Iscrizione Lezione (Frontend - UC06)

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant corsiView as CorsiView
    participant bookingView as BookingView
    participant presenter as BookingPresenter
    participant bus as Observer (eventBus)
    participant api as APIService
    participant backend as Backend

    user ->>+ corsiView: clicca "Iscriviti" su lezione
    corsiView ->>+ bus: notify(LESSON_SELECT_EVENT, {lessonId})
    bus ->>+ presenter: callback
    
    presenter ->>+ api: createEnrollment({lessonId})
    api ->>+ backend: POST /courses/enroll
    
    alt posti disponibili
        backend -->>- api: {success: true, bookingId, amount}
        api -->>- presenter: {success: true, bookingId, amount}
        presenter ->>+ bookingView: display({booking: {bookingId, amount}})
        bookingView -->>- user: mostra riepilogo iscrizione
        
        user ->>+ bookingView: conferma e vai al pagamento
        bookingView ->>+ bus: notify(BOOKING_CONFIRM_EVENT, {bookingId})
        bus ->>+ presenter: callback
        
        Note over presenter: Procede a UC08 per il pagamento
        presenter ->>+ bookingView: navigazione a pagamento
        bookingView -->>- user: vai a pagamento
        
    else posti esauriti
        backend -->> api: {success: false, error: "Posti esauriti"}
        api -->> presenter: {success: false, error}
        presenter ->>+ corsiView: display(error)
        corsiView -->>- user: mostra errore aggiornato
    end
```
