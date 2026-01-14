# Modulo Prenotazione - Frontend (Calendario)

## Casi d'uso correlati
- **UC03**: Visualizzazione Disponibilità Campi (Selezione data e slot)
- **UC04**: Prenotazione Campo Sportivo
- **UC05**: Visualizzazione Corsi (Selezione data e visualizzazione disponibilità)
- **UC06**: Iscrizione a una Lezione

## Panoramica

Il modulo **prenotazione** gestisce la selezione di **data** e **slot orari** per un campo/corso, a partire da uno sport o corso selezionato nel modulo *sport-selection*.

Componenti principali:
- `CalendarView`
- `CalendarPresenterV2`
- `DatePicker`
- `SlotPicker`
- `ReservationState`
- `CartView` / `CartPresenter`
- `APIService`
- `SlotLoadStrategy`
- `NavigateCommand` (verso il modulo pagamento)

## Diagramma di attività (selezione e conferma slot)

```mermaid
flowchart TD

Start([Inizio da sport-selection]) --> ShowWeek[Mostra settimana corrente]

ShowWeek --> UserSelectDate[Utente seleziona giorno]

UserSelectDate --> LoadSlots[Carica slot occupati per la settimana]

LoadSlots --> ShowSlots[Mostra slot liberi/occupati]

  

ShowSlots --> UserToggleSlots[Utente seleziona/deseleziona slot]

UserToggleSlots --> UpdateState[Aggiorna stato e carrello]

UpdateState --> Choice{Procedere al pagamento?}

  

Choice -->|No| End1([Fine])

Choice -->|Sì| GoToPayment[Esegui NavigateCommand verso pagamento]

GoToPayment --> End2([Fine])
```

## Diagramma di sequenza (frontend)



```mermaid
sequenceDiagram

actor Utente

participant MainPresenter

participant CalendarView

participant CalendarPresenter

participant EventBus

participant SlotLoadStrategy

participant APIService

participant CartView

  

MainPresenter ->> CalendarView: crea CalendarView

MainPresenter ->> CalendarPresenter: crea Presenter (view, strategy)

  

CalendarView ->> EventBus: CALENDAR_LOAD_EVENT

EventBus ->> CalendarPresenter: CALENDAR_LOAD_EVENT

  

Note over CalendarPresenter: inizializza stato(date, slot, cart)

CalendarPresenter ->> CalendarView: display settimana

  

Utente ->> CalendarView: seleziona data

CalendarView ->> EventBus: DATE_SELECTED_EVENT

EventBus ->> CalendarPresenter: DATE_SELECTED_EVENT

  

Note over CalendarPresenter: aggiorna stato(selectedDate)

CalendarPresenter ->> SlotLoadStrategy: load(date)

SlotLoadStrategy ->> APIService: getOccupiedSlotsForWeek()

APIService -->> SlotLoadStrategy: slot occupati

SlotLoadStrategy -->> CalendarPresenter: slot occupati

  

Note over CalendarPresenter: aggiorna stato(occupiedSlots)

CalendarPresenter ->> CalendarView: display slot disponibili

  

Utente ->> CalendarView: seleziona slot

CalendarView ->> EventBus: SLOT_SELECTED_EVENT

EventBus ->> CalendarPresenter: SLOT_SELECTED_EVENT

  

Note over CalendarPresenter: aggiorna stato(selectedSlots)

CalendarPresenter ->> CalendarView: aggiorna selezione

  

Utente ->> CartView: clic "Paga ora"

CartView ->> EventBus: PAYMENT_PROCEED_EVENT

EventBus ->> CalendarPresenter: PAYMENT_PROCEED_EVENT

  

CalendarPresenter ->> MainPresenter: esegue onConfirmCommand(naviga al pagamento)
```

## Diagramma delle classi

```mermaid
classDiagram

class View {

<<abstract>>

+display(data)

+template() string

#bindEvents()

}

  

class CalendarView {

+display(data)

+template() string

#bindEvents()

}

  

class DatePicker {

+display(data)

+template() string

#bindEvents()

}

  

class SlotPicker {

+display(data)

+template() string

#bindEvents()

}

  

class CartView {

+display(data)

+template() string

#bindEvents()

}

  

class Presenter {

<<abstract>>

#view: View

#config: Object

+update()

#handleViewEvents()

}

  

class CalendarPresenterV2 {

-views: Object

-state: ReservationState

+update()

#handleViewEvents()

-initComponents()

-updateDatePicker()

-updateSlotPicker()

}

  

class CartPresenter {

+update()

#handleViewEvents()

}

  

class ReservationState {

+week: Date[]

+selectedDate: string

+selectedSlots: Set~string~

+occupiedSlots: string[]

+changeWeek(delta, callback)

+clear()

}

  

class SlotLoadStrategy {

<<interface>>

+load(data) Promise

}

  

class APIService {

<<interface>>

+getOccupiedSlotsForWeek(startDate: string) Promise

}

  

View <|-- CalendarView

View <|-- DatePicker

View <|-- SlotPicker

View <|-- CartView

Presenter <|-- CalendarPresenterV2

Presenter <|-- CartPresenter

  

CalendarPresenterV2 o-- ReservationState

CalendarPresenterV2 o-- SlotLoadStrategy

CalendarPresenterV2 o-- CartView

CartPresenter o-- CartView

SlotLoadStrategy --> APIService
```
