# Modulo Selezione sport - Frontend

## Casi d'uso correlati
- **UC03**: Visualizzare disponibilità campi (parte di selezione campo)
- **UC05**: Visualizzare corsi

## Panoramica

Il modulo **sport-selection** gestisce la selezione iniziale di un **campo sportivo** o di un **corso**.
È il punto di ingresso per i flussi di prenotazione (UC03/UC04 e UC05/UC06).

## Diagramma di attività (selezione campo/corso)

```mermaid
flowchart TD
    Start([Inizio]) --> ShowList[Mostra lista campi/corsi]
    ShowList --> UserSelect[Utente seleziona un elemento]
    ShowList --> NoItems{Nessun elemento disponibile?}

    NoItems -->|Sì| EmptyMsg[Mostra messaggio]
    EmptyMsg --> End1([Fine])
    NoItems -->|No| UserSelect

    UserSelect --> NavigateCalendar[Esegui NavigateCommand verso calendario]
    NavigateCalendar --> End2([Fine])
```

## Diagramma di sequenza (frontend)

```mermaid
sequenceDiagram
    actor Utente
    participant MainPresenter
    participant SportSelectionView
    participant SportSelectionPresenter
    participant EventBus
    participant LoadStrategy
    participant APIService

    MainPresenter ->> SportSelectionView: crea view
    MainPresenter ->> SportSelectionPresenter: crea presenter

    SportSelectionView ->> EventBus: SPORTS_LOAD_EVENT
    EventBus ->> SportSelectionPresenter: SPORTS_LOAD_EVENT

    SportSelectionPresenter ->> LoadStrategy: load(itemType)
    LoadStrategy ->> APIService: getSports / getCourses
    APIService -->> LoadStrategy: items
    LoadStrategy -->> SportSelectionPresenter: items

    SportSelectionPresenter ->> SportSelectionView: display(items)
    SportSelectionView -->> Utente: mostra lista

    Utente ->> SportSelectionView: seleziona elemento
    SportSelectionView ->> EventBus: SPORT_SELECTED_EVENT
    EventBus ->> SportSelectionPresenter: SPORT_SELECTED_EVENT

    Note over SportSelectionPresenter: esegue onSelectedCommand(navigazione)
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

  

class SportSelectionView {

-config: Object

+display(data)

+template() string

#_bindEvents()

}

  

class Presenter {

<<abstract>>

#view: View

#config: Object

+update()

#handleViewEvents()

}

  

class SportSelectionPresenter {

#view: SportSelectionView

#config

}

  

class APIService {

<<interface>>

+getSports() Promise

+getCourses() Promise

}

  

class LoadStrategy {

<<interface>>

+load(data) Promise

}

  

class FieldsLoadStrategy {

+load(data) Promise

}

  

class CoursesLoadStrategy {

+load(data) Promise

}

  

class Command {

<<interface>>

+execute()

}

  

class NavigateCommand {

-route: string

+execute()

}

  

View <|-- SportSelectionView

Presenter <|-- SportSelectionPresenter

LoadStrategy <|-- FieldsLoadStrategy

LoadStrategy <|-- CoursesLoadStrategy

Command <|-- NavigateCommand

  

SportSelectionPresenter o-- LoadStrategy

SportSelectionPresenter o-- Command

FieldsLoadStrategy --> APIService

CoursesLoadStrategy --> APIService
```
