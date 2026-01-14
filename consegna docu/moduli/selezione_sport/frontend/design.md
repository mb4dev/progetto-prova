# Modulo Selezione Sport - Frontend

## Casi d'uso Correlati
- **UC03**: Visualizzazione Disponibilità Campi
- **UC05**: Visualizzare corsi

Questo modulo gestisce la visualizzazione e la selezione dei campi e degli sport disponibili.

## Diagramma di Attività

```mermaid 
flowchart TD
    Start([Inizio]) --> SelectSport[Utente seleziona la pagina di prenotazione]
    SelectSport --> LoadFields[Sistema carica i dati per il tipo di prenotazione selezionato]
    LoadFields --> ShowFields[Mostra elenco campi/corsi disponibili]
    ShowFields --> SelectField[Utente seleziona un elemento]
    SelectField --> Navigate[Naviga a Prenotazione]
    Navigate -->End([Fine])
```

### Flusso Selezione (con Strategy e Command)

```mermaid
sequenceDiagram
    autonumber
    
    actor user as Utente
    participant main as MainPresenter
    participant view as SportSelectionView
    participant presenter as SportSelectionPresenter
    participant strategy as LoadStrategy
    participant api as APIService
    participant bus as Observer
    participant command as Command

    main ->>+ presenter: init()
    presenter ->>+ strategy: load()
    strategy ->>+ api: get data
    api -->>- strategy: data
    strategy -->>- presenter: {data}
    
    presenter ->>+ view: display({items: data})
    view -->>- user: mostra lista elementi
    
    user ->>+ view: seleziona elemento
    view ->>+ bus: notify(SPORT_SELECTED_EVENT)
    bus ->>+ presenter: handle event
    
    presenter ->>+ command: execute()
    command -->>- presenter: action completed (e.g. navigation)
```


## Diagramma delle Classi

```mermaid
classDiagram
    class Presenter {
        <<interface>>
    }

    class SportSelectionPresenter {

    }

    class SportSelectionView {
        +display(data)
    }

    class SportCard {
        +render(sportData)
    }

    class LoadStrategy {
        <<interface>>
        +load() Promise
    }

    class Command {
        <<interface>>
        +execute()
    }

    class ConcreteLoadStrategy {
        +load()
    }

    class ConcreteCommand {
        +execute()
    }

    SportSelectionPresenter --|> Presenter
    SportSelectionPresenter --> SportSelectionView : manages
    SportSelectionView ..> SportCard : uses
    SportSelectionPresenter --> LoadStrategy : uses
    SportSelectionPresenter --> Command : executes
    ConcreteLoadStrategy ..|> LoadStrategy
    ConcreteCommand ..|> Command
```