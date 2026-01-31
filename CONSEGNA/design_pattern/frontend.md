# Design Patterns Frontend

Questo documento descrive i principali design pattern implementati nell'architettura frontend del sistema.

---

## Model-View-Presenter (MVP)

### Scopo
Separare la logica di presentazione dalla logica di business e dalla visualizzazione, migliorando la testabilità e la manutenibilità del codice frontend.

### Componenti

```mermaid
classDiagram
class View {
    <<interface>>
    +display(data: Object)
    +template() string
    #bindEvents()
}

class Presenter {
    <<abstract>>
    #view: View
    #api: APIService
    +init()
    +update()
    #handleViewEvents()
}

class ConcreteView {
    +display(data: Object)
    +template() string
    #bindEvents()
}

class ConcretePresenter {
    +init()
    +update()
    #handleViewEvents()
}

ConcreteView ..|> View
ConcretePresenter --|> Presenter
ConcretePresenter --> View
ConcretePresenter --> APIService
```

---

## Observer Pattern

### Scopo
Permettere la comunicazione tra View e Presenter, disaccoppiando i componenti.

### Struttura

```mermaid
classDiagram
class Observer {
    <<interface>>
    +subscribe(event: string, callback: Function)
    +notify(event: string, data: Object)
}

class DefaultObserver {
    -listeners: Map
    +subscribe(event: string, callback: Function)
    +notify(event: string, data: Object)
}

class View {
    +notify(event, data)
}

class Presenter {
    +subscribe(event, callback)
}

DefaultObserver ..|> Observer
View ..> Observer : usa
Presenter ..> Observer : usa
```

---

## Factory Pattern (Frontend)

### Scopo
Centralizzare la creazione delle View e dei Presenter, disaccoppiando il router dalla logica di istanziazione dei componenti.

### Struttura

```mermaid
classDiagram
    class View {
        <abstract>
    }

    class ViewFactory {
        +create(route: string): (View, Presenter)
        -createViewA()
        -createViewB()
    }

    class Routes {
        <<enumeration>>
    }


    ViewFactory ..> Routes : usa
    ViewFactory ..> ViewA : crea
    ViewFactory ..> ViewB : crea
```

---

## Strategy Pattern (Frontend)

### Scopo
Incapsulare diverse logiche di caricamento dati (es. Campi vs Corsi) permettendo di riutilizzare lo stesso Presenter per casi d'uso diversi.

### Struttura

```mermaid
classDiagram
    class LoadStrategy {
        <<interface>>
        +load() : Promise
    }

    class FieldsLoadStrategy {
        +load()
    }

    class CoursesLoadStrategy {
        +load()
    }

    class SportSelectionPresenter {
        -loadStrategy: LoadStrategy
        +loadData()
    }

    class APIService {
        +getSports()
        +getCourses()
    }

    FieldsLoadStrategy --|> LoadStrategy
    CoursesLoadStrategy --|> LoadStrategy
    FieldsLoadStrategy ..> APIService : calls
    CoursesLoadStrategy ..> APIService : calls
    SportSelectionPresenter --> LoadStrategy : uses
```

---

## Command Pattern (Frontend)

### Scopo
Disaccoppiare il Presenter dall'azione effettiva da eseguire (es. navigazione), iniettando il comando da eseguire.

### Struttura

```mermaid
classDiagram
    class Command {
        <<interface>>
        +execute()
    }

    class NavigateToCalendarCommand {
        +execute()
    }

    class SportSelectionPresenter {
        -onSelectedCommand: Command
        +onItemSelect()
    }

    class EventBus {
        +notify(event, data)
    }

    NavigateToCalendarCommand --|> Command
    NavigateToCalendarCommand ..> EventBus : uses
    SportSelectionPresenter --> Command : invokes
```

---

## Gestione Navigazione (Flusso Event-Driven)

Il seguente diagramma mostra come il pattern Observer e MVP collaborano per gestire la navigazione tra le sezioni dell'applicazione.

### Diagramma di Sequenza (Navigazione)

```mermaid
sequenceDiagram
    autonumber

    actor User as Utente
    participant SideMenu as MainView (SideMenu)
    participant Observer as Observer (eventBus)
    participant MainP as MainPresenter
    participant SubP as SubPresenter
    participant SubV as SubView
    participant MainContent as MainView (Content Area)

    User ->> SideMenu: Click bottone navigazione
    SideMenu ->>+ Observer: notify(MAIN_SELECT_EVENT, {main: route})
    Observer ->>+ MainP: callback
    
    Note over MainP: Look up route in registry
    
    MainP ->>+ SubV: new SubView()
    MainP ->>+ SubP: new SubPresenter(view, service)
    MainP ->> SubP: init()
    MainP ->>+ MainContent: display()
    
    MainContent ->> MainContent: updateActiveTab()
    MainContent -->>- MainContent: append SubView al DOM
    
    deactivate SubP
    deactivate SubV
    deactivate MainP
    deactivate Observer
```
