# Modulo Abbonamenti - Frontend

## Casi d'uso correlati
- **UC09**: Acquistare abbonamento
- **UC13**: Gestire tariffe e abbonamenti (parte lato utente)
## Panoramica

Il modulo **abbonamenti** consente all'utente di:
- visualizzare la lista degli abbonamenti disponibili,
- selezionare un abbonamento da acquistare,
- procedere al pagamento.

## Diagramma di sequenza (frontend)

```mermaid
sequenceDiagram
    actor user as Utente
    participant main as MainPresenter
    participant view as SubscriptionView
    participant presenter as SubscriptionPresenter
    participant bus as eventBus
    participant api as APIService
    participant cmd as NavigateCommand

    main ->>+ view: crea SubscriptionView
    main ->>+ presenter: crea SubscriptionPresenter(view, {onSelectedCommand})

    view ->> bus: notify(SUBSCRIPTION_LOAD_EVENT)
    bus ->> presenter: callback SUBSCRIPTION_LOAD_EVENT

    presenter ->> api: getSubscriptions()
    api -->> presenter: Response(success, data[])
    presenter ->> view: display({items})
    view -->> user: mostra lista abbonamenti

    user ->> view: clic su "Acquista"
    view ->> bus: notify(SUBSCRIPTION_SELECTED_EVENT, {id})
    bus ->> presenter: callback SUBSCRIPTION_SELECTED_EVENT

    presenter ->> cmd: execute()
    cmd ->> main: navigazione verso MAIN_PAYMENT
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

    class SubscriptionView {
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

    class SubscriptionPresenter {
        +update()
        #handleViewEvents()
    }

    class APIService {
        <<interface>>
        +getSubscriptions() Promise
    }

    class Command {
        <<interface>>
        +execute()
    }

    class NavigateCommand {
        +execute()
    }

    View <|-- SubscriptionView
    Presenter <|-- SubscriptionPresenter
    Command <|-- NavigateCommand

    SubscriptionPresenter --> APIService
    SubscriptionPresenter o-- NavigateCommand
```



