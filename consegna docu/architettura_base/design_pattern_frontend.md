# Design Patterns Frontend

Questo documento descrive i principali design pattern utilizzanti nel frontend, mostrando un singolo esempio di utilizzo.

## 1. Factory Pattern

Il **Factory Pattern** è stato introdotto per centralizzare la logica di creazione delle View e dei Presenter. Questo permette di disaccoppiare il router dalla logica di istanziazione dei componenti e facilita l'iniezione delle dipendenze (come le strategie o i comandi).

### Implementazione

- **`ComponentFactory`**: Classe statica che funge da factory unica per l'applicazione. In base alla rotta richiesta, istanzia e configura la coppia View-Presenter appropriata.

### Diagramma delle Classi

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

## 2. Strategy Pattern

Lo **Strategy Pattern** è utilizzato per incapsulare le diverse logiche di caricamento dei dati (es. caricare i Campi vs caricare i Corsi). Questo permette di riutilizzare lo stesso `SportSelectionPresenter` per casi d'uso diversi (UC03 Prenotazione Campi e UC05 Prenotazione Corsi), cambiando solo la strategia iniettata.

### Implementazione

- **`LoadStrategy`**: Interfaccia che definisce il metodo `load()`.
- **`FieldsLoadStrategy`**: Implementazione che recupera la lista dei campi
- **`CoursesLoadStrategy`**: Implementazione che recupera la lista dei corsi
- **`SportSelectionPresenter`**: Classe che riceve una `LoadStrategy` nel costruttore e la utilizza per recuperare i dati, senza conoscere i dettagli dell'implementazione.

### Diagramma delle Classi

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

## 3. Command Pattern

Il **Command Pattern** è stato utilizzato per disaccoppiare il `Presenter` dall'azione effettiva da eseguire (es. navigazione). In questo modo, il `Presenter` non deve sapre che azione eseguire, ma deve solo eseguire il comando iniettato.

### Implementazione

- **`Command`**: Interfaccia che definisce il metodo `execute()`.
- **`NavigateToCalendarCommand`**: Comando concreto che pubblica un evento sul bus per navigare alla vista del calendario.
- **`SportSelectionPresenter`**: Riceve un comando (`onSelectedCommand`) nella configurazione e lo esegue quando un elemento viene selezionato.

### Diagramma delle Classi

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
