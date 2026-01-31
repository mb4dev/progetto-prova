# Modulo Storico - Frontend

## Casi d'uso correlati
- **UC10**: Visualizzare storico prenotazioni e pagamenti
- **UC07**: Cancellare prenotazione (entry point dalla lista storico)

## Panoramica

Il modulo **storico** gestisce:
- la visualizzazione di una lista di **prenotazioni** e **pagamenti** effettuati dall'utente (UC10)
- l'avvio del flusso di **cancellazione prenotazione** a partire dallo storico (UC07).

## Diagramma di sequenza (frontend)

```mermaid
sequenceDiagram
    actor user as Utente
    participant main as MainPresenter
    participant view as HistoryView
    participant presenter as HistoryPresenter
    participant bus as eventBus
    participant api as APIService

    main ->>+ view: crea HistoryView
    main ->>+ presenter: crea HistoryPresenter(view)
    view ->> bus: notify HISTORY_LOAD_EVENT
    bus ->> presenter: callback HISTORY_LOAD_EVENT

    presenter ->> api: getHistory()
    api -->> presenter: Response(success, data[])
    presenter ->> view: display({items})
    view -->> user: mostra elenco prenotazioni/pagamenti

    user ->> view: clic su "Cancella" su una prenotazione futura
    view ->> bus: notify(BOOKING_CANCEL_REQUEST_EVENT, {bookingId})
    bus ->> presenter: callback BOOKING_CANCEL_REQUEST_EVENT

    presenter ->> api: cancelBooking(bookingId)
    api -->> presenter: Response(success, data)

    alt success
        presenter ->> view: display({items aggiornati})
        view -->> user: mostra conferma cancellazione
    else errore
        presenter ->> view: display({error})
        view -->> user: mostra messaggio "Cancellazione non consentita" o errore generico
    end
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

    class HistoryView {
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

    class HistoryPresenter {
        +update()
        #handleViewEvents()
    }

    class APIService {
        <<interface>>
        +getHistory() Promise
        +cancelBooking(bookingId: int) Promise
    }

    View <|-- HistoryView
    Presenter <|-- HistoryPresenter
    HistoryPresenter --> APIService
```

