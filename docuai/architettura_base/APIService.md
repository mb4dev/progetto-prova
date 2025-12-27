# APIService Interface

Questa è l'interfaccia utilizzata nel progetto per la **comunicazione con il backend** tramite chiamate API HTTP.

## Descrizione

L'interfaccia `APIService` definisce il contratto per effettuare chiamate HTTP al backend. Fornisce metodi per le operazioni di autenticazione e altre funzionalità dell'applicazione.

## Metodi

```mermaid
classDiagram
class APIService {
    <<interface>>
    +login(email: string, password: string) Promise
    +register(email: string, name: string, password: string) Promise
}
```

## Responsabilità

- Effettuare chiamate HTTP al backend
- Gestire le risposte e gli errori
- Restituire Promise per gestione asincrona

## Implementazioni

Le seguenti classi implementano questa interfaccia:

```mermaid
classDiagram
class APIService {
    <<interface>>
    +login(email: string, password: string) Promise
    +register(email: string, name: string, password: string) Promise
    +getProfile() Promise
    +updateProfile(data: Object) Promise
    +getFields() Promise
    +getOccupiedSlots(fieldId: int, startDate: string, endDate: string) Promise
    +createBooking(data: Object) Promise
}



class FetchAPIService {
    -baseURL: string
    -token: string
    +login(email: string, password: string) Promise
    +register(email: string, name: string, password: string) Promise
    +getProfile() Promise
    +updateProfile(data: Object) Promise
}

FetchAPIService ..|> APIService
```

### FetchAPIService
- **Scopo**: Implementazione reale usando Fetch API
- **Utilizzo**: Produzione
- **Caratteristiche**: Gestione token JWT, error handling, retry logic

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa interfaccia:

```mermaid
classDiagram
class APIService {
    <<interface>>
}

class Presenter {
    <<abstract>>
    #api: APIService
}

class Promise {}

class Backend {
    <<HTTP Server>>
}

class FetchAPIService {
    +login() Promise
}

APIService --> Promise : restituisce
Presenter --> APIService : utilizza
FetchAPIService ..|> APIService : implementa
FetchAPIService ..> Backend : comunica con
```

### Relazioni
- **Utilizzata da**: `Presenter` (e tutte le sue sottoclassi)
- **Restituisce**: `Promise` - per gestione asincrona
- **Comunica con**: Backend HTTP - tramite fetch
- **Implementata da**: `MockAPIService`, `FetchAPIService`

