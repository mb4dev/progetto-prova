# Modulo Pagamento - Frontend

## Casi d'uso correlati
- **UC04**: Prenotare campo sportivo (parte di conferma e riepilogo)
- **UC06**: Iscriversi a una lezione di un corso (parte di conferma e riepilogo)
- **UC08**: Effettuare pagamento singolo

## Panoramica

Il modulo **pagamento** gestisce:
- l'aggregazione delle prenotazioni selezionate dall'utente in un **carrello**,
- il **riepilogo** degli elementi da pagare,
- la **conferma del pagamento** e l'eventuale svuotamento del carrello.

La logica di chiamata all'API di pagamento è incapsulata in una `PaymentStrategy`, iniettata nel `PaymentPresenter` dalla `ViewFactory`. In questo modo è possibile differenziare:

- il **pagamento singolo** (UC08), che usa `NormalPaymentStrategy` e opera sui dati presenti nel carrello;
- il **pagamento abbonamento** (UC09), che usa `SubscriptionPaymentStrategy` e opera sui dati dell'abbonamento selezionato.

## Diagramma di attività (carrello e pagamento)

```mermaid
flowchart TD
    Start([Inizio da calendario]) --> AddItems[Aggiunta elementi al carrello]
    AddItems --> ShowCart[Mostra contenuto carrello]
    ShowCart --> Choice{Carrello vuoto?}

    Choice -->|Sì| EndEmpty([Fine - nessun pagamento])
    Choice -->|No| UserGoToPayment[Utente clicca bottone conferma]

    UserGoToPayment --> NavigatePayment[Esegui NavigateCommand verso vista pagamento]
    NavigatePayment --> ShowSummary[Mostra riepilogo pagamento + form carta]

    ShowSummary --> UserConfirm{Conferma pagamento?}
    UserConfirm -->|No| Cancel[Annulla / torna indietro]
    Cancel --> EndCancel([Fine])

    UserConfirm -->|Sì| ConfirmPayment[Esegui comando pagament]
    ConfirmPayment --> ClearCart[Svuota carrello / mostra conferma]
    ClearCart --> EndOk([Fine])
```

## Diagramma di sequenza (frontend - pagamento singolo)

```mermaid
sequenceDiagram

actor Utente

participant CalendarPresenter

participant CartService

participant EventBus

participant CartPresenter

participant CartView

participant MainPresenter

participant PaymentPresenter

participant PaymentView

participant PaymentStrategy
participant ConfirmPaymentCommand

  

CalendarPresenter ->> CartService: addItem()

Note over CartService: stato carrello aggiornato

CartService ->> EventBus: CART_UPDATED

  

EventBus ->> CartPresenter: CART_UPDATED

CartPresenter ->> CartView: display(cart, total)

CartView -->> Utente: mostra carrello

  

Utente ->> CartView: clic "Paga ora"

CartView ->> EventBus: PAYMENT_PROCEED_EVENT

EventBus ->> CalendarPresenter: PAYMENT_PROCEED_EVENT

  

CalendarPresenter ->> MainPresenter: naviga a pagamento

  

MainPresenter ->> PaymentView: crea view

MainPresenter ->> PaymentPresenter: crea presenter

  

PaymentPresenter ->> CartService: getItems(), getTotal()

CartService -->> PaymentPresenter: dati carrello

PaymentPresenter ->> PaymentView: display riepilogo

  

Utente ->> PaymentView: conferma pagamento

PaymentView ->> EventBus: PAYMENT_CONFIRM_EVENT

EventBus ->> PaymentPresenter: PAYMENT_CONFIRM_EVENT

  

PaymentPresenter ->> PaymentStrategy: pay({items, total})
PaymentStrategy ->> APIService: processSinglePayment(...)
APIService -->> PaymentStrategy: Response(success, data)
PaymentStrategy -->> PaymentPresenter: Promise resolved
PaymentPresenter ->> CartService: clear()
PaymentPresenter ->> ConfirmPaymentCommand: execute({response})

Note over PaymentStrategy: incapsula la chiamata API\nper il pagamento singolo
```

## Diagramma delle classi

```mermaid
classDiagram

class CartService {

-items CartItem[]

+addItem(item: CartItem)

+removeItem(index: number)

+clear()

+getItems() CartItem[]

+getTotal() number

}

  

class CartItem {

+sport: Object

+date: string

+slots: string[]

+price: number

}

  

class View {

<<abstract>>

+display(data)

+template() string

#bindEvents()

}

  

class CartView {

  

+display(data)

+template() string

#bindEvents()

}

  

class PaymentView {

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

  

class CartPresenter {

+update()

#handleViewEvents()

}

  

class PaymentPresenter {

+update()

#handleViewEvents()

}

  

class Command {

<<interface>>

+execute()

}

  

class ConfirmPaymentCommand {

+execute()

}

  

View <|-- CartView

View <|-- PaymentView

Presenter <|-- CartPresenter

Presenter <|-- PaymentPresenter

Command <|-- ConfirmPaymentCommand

  

CartPresenter o-- CartService

CartPresenter o-- CartView

PaymentPresenter o-- CartService

PaymentPresenter o-- ConfirmPaymentCommand

PaymentPresenter o-- CartView

PaymentPresenter o-- PaymentView
```

