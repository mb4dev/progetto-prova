# Modulo Selezione Sport - Frontend

## Casi d'uso Correlati
- **UC03**: Visualizzazione Disponibilità Campi (Selezione Sport iniziale)

Questo modulo gestisce la visualizzazione e la selezione degli sport disponibili.

## Componenti

### SportSelectionPresenter
Gestisce il caricamento degli sport e la selezione.
- Utilizza una `loadStrategy` per caricare i dati (es. da API o statici).
- Sottoscrive a `SPORTS_LOAD_EVENT` per avviare il caricamento.
- Sottoscrive a `SPORT_SELECTED_EVENT` per eseguire un comando (`onSelectedCommand`) quando un utente seleziona uno sport.

### SportSelectionView
Visualizza la griglia o la lista degli sport.
- Riceve i dati dal presenter e li renderizza.
- Emette `SPORT_SELECTED_EVENT`.

### SportCard
Componente UI che rappresenta un singolo sport.

## Diagramma delle Classi

```mermaid
classDiagram
    class Presenter {
        <<interface>>
    }

    class SportSelectionPresenter {
        -_config
        +_handleViewEvents()
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

## Diagramma di Attività

```mermaid 
flowchart TD
    Start([Inizio]) --> SelectSport[Utente seleziona lo sport]
    SelectSport --> LoadFields[Sistema carica campi per lo sport selezionato]
    LoadFields --> ShowFields[Mostra elenco campi disponibili]
    ShowFields --> SelectField[Utente seleziona un campo]
    SelectField --> Navigate[Naviga a Calendario]
```

## Diagrammi di Sequenza

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
    participant bus as Observer (eventBus)
    participant command as Command

    main ->>+ presenter: init()
    Note over presenter: _config.loadStrategy.load()
    presenter ->>+ strategy: load()
    strategy ->>+ api: get data
    api -->>- strategy: data
    strategy -->>- presenter: {data}
    
    presenter ->>+ view: display({items: data})
    view -->>- user: mostra lista elementi
    
    user ->>+ view: seleziona elemento
    view ->>+ bus: notify(SPORT_SELECTED_EVENT)
    bus ->>+ presenter: handle event
    
    Note over presenter: _config.onSelectedCommand.execute()
    presenter ->>+ command: execute()
    command -->>- presenter: action completed (e.g. navigation)
```
