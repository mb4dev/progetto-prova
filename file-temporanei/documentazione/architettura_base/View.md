# View Interface

Questa è l'interfaccia utilizzata nel progetto per la **visualizzazione dei componenti** nel pattern MVP (Model-View-Presenter).

## Descrizione

L'interfaccia `View` definisce il contratto per tutti i componenti di visualizzazione dell'applicazione frontend. Ogni View è responsabile della renderizzazione dell'interfaccia utente e della gestione degli eventi DOM.

## Metodi

```mermaid
classDiagram
class View {
    <<interface>>
    +display(data) 
    +template() string
    #bindEvents() 
}
```

## Responsabilità

- **display(data)**: Aggiorna la visualizzazione con nuovi dati
- **template()**: Restituisce il template HTML della view
- **bindEvents()**: Collega gli eventi DOM agli handler

## Pattern

Implementa la parte **View** del pattern **MVP (Model-View-Presenter)** con comunicazione tramite **Observer Pattern**.

## Implementazioni

Le seguenti classi implementano questa interfaccia:

```mermaid
classDiagram
class View {
    <<interface>>
    +display(data)
    +template() string
    #bindEvents()
}

class LoginView {
    +display(data)
    +template() string
    #bindEvents()
    -renderForm()
}

class RegisterView {
    +display(data)
    +template() string
    #bindEvents()
    -renderForm()
}

class MainView {
    -mainContent: HTMLElement
    -activeRoute: string
    +display(data)
    +template() string
    #bindEvents()
    -updateActiveTab()
}

class ProfileView {
    -profileData: Object
    -isEditing: bool
    +display(data)
    +template() string
    #bindEvents()
}

LoginView ..|> View
RegisterView ..|> View
MainView ..|> View
ProfileView ..|> View
```

### Componenti Riutilizzabili
- **SportCard** - Card per visualizzare singolo sport
  - Utilizzata da: `CampiView`
  - Responsabilità: Renderizzare un singolo sport con immagine, prezzo

``` mermaid 
classDiagram
class SportCard {
    -id: string
    -title: string
    -image: string
    -price: string
}
SportCard ..|> View
```


## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa interfaccia:

```mermaid
classDiagram
class View {
    <<interface>>
    +display(data)
    +template() string
    #bindEvents()
}

class Observer {
    <<interface>>
    +notify(event: string, data: Object)
}

class Presenter {
    <<abstract>>
    #view: View
    +init()
}

class LoginView {
    +display(data)
}

class HTMLElement {
    <<DOM>>
}

View ..> Observer : utilizza per notify
Presenter --> View : utilizza
LoginView ..|> View : implementa
View ..> HTMLElement : renderizza
```

### Relazioni
- **Comunica con**: `Presenter` - tramite pattern Observer
- **Utilizza**: `Observer` (DefaultObserver) - per notificare eventi
- **Renderizza**: `HTMLElement` - elementi DOM
- **Implementata da**: `LoginView`, `RegisterView`, `MainView`, `CampiView`, `BookingView`, `ProfileView`

