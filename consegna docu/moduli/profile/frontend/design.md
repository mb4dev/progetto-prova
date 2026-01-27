# Modulo Profilo - Frontend

## Casi d'uso correlati
- **UC02**: Visualizzare e gestire profilo utente

## Panoramica

Il modulo **profilo** permette all'utente di visualizzare le proprie informazioni personali e di modificarle. Utilizza il pattern **Command** per gestire l'aggiornamento dei dati e invia notifiche globali tramite l'**EventBus** quando il profilo viene aggiornato

## Diagramma di attività (visualizzazione e modifica)

```mermaid
flowchart TD
    Start([Inizio]) --> LoadData[Carica dati da localStorage]
    LoadData --> DisplayView[Mostra profilo in modalità visualizzazione]
    
    DisplayView --> Choice{Cosa fare?}
    
    Choice -->|Clic su Modifica| EditMode[Passa a modalità editing]
    Choice -->|Esci| End([Fine])
    
    EditMode --> UserInputs[Utente modifica i campi]
    UserInputs --> SaveChoice{Salvare?}
    
    SaveChoice -->|Annulla| DisplayView
    SaveChoice -->|Salva| ExecuteCommand[Esegui ProfileUpdateCommand]
    
    ExecuteCommand --> UpdateStorage[Aggiorna localStorage]
    UpdateStorage --> NotifyGlobal[Notifica PROFILE_UPDATED]
    NotifyGlobal --> DisplayView
```

## Diagramma di sequenza (frontend)

```mermaid
sequenceDiagram
    actor Utente
    participant MainPresenter
    participant ProfileView
    participant ProfilePresenter
    participant eventBus
    participant ProfileUpdateCommand
    participant localStorage

    MainPresenter ->> ProfileView: crea ProfileView
    MainPresenter ->> ProfilePresenter: crea ProfilePresenter(view, {onUpdateCommand})
    
    ProfilePresenter ->> localStorage: getItem("user")
    localStorage -->> ProfilePresenter: dati utente
    ProfilePresenter ->> ProfileView: display({profile})
    ProfileView -->> Utente: mostra i dati
    
    Utente ->> ProfileView: clic su "Modifica"
    Note over ProfileView: passa internamente a modalità edit
    ProfileView -->> Utente: mostra input compilati
    
    Utente ->> ProfileView: clic su "Salva"
    ProfileView ->> eventBus: notify(PROFILE_UPDATE_EVENT, data)
    eventBus ->> ProfilePresenter: callback PROFILE_UPDATE_EVENT
    
    ProfilePresenter ->> ProfileUpdateCommand: execute(data)
    ProfileUpdateCommand ->> localStorage: setItem("user", data)
    ProfileUpdateCommand -->> ProfilePresenter: newUser
    
    ProfilePresenter ->> ProfileView: display({profile: newUser, mode: "view"})
    ProfilePresenter ->> eventBus: notify(PROFILE_UPDATED, newUser)
    
    Note over ProfileView: torna in modalità visualizzazione
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

    class ProfileView {
        -profileData: Object
        -isEditing: boolean
        +display(data)
        +render()
        +template() string
        #bindEvents()
    }

    class Presenter {
        <<abstract>>
        #view: View
        #config: Object
        +init()
        +update()
        #handleViewEvents()
    }

    class ProfilePresenter {
        +init()
        #handleViewEvents()
    }

    class Command {
        <<interface>>
        +execute(data)
    }

    class ProfileUpdateCommand {
        +execute(data) Promise
    }

    View <|-- ProfileView
    Presenter <|-- ProfilePresenter
    Command <|-- ProfileUpdateCommand
    
    ProfilePresenter o-- ProfileUpdateCommand 
    ProfilePresenter o-- ProfileView
```
