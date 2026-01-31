# Router (Classe Astratta)

Questa è la classe astratta utilizzata nel progetto per il **routing delle richieste HTTP** verso i controller appropriati.

## Descrizione

La classe astratta `Router` definisce la struttura base per gestire il routing delle richieste HTTP. Coordina il parsing dell'URL, la creazione del controller e l'invio della risposta.


## Responsabilità

- Ricevere le richieste HTTP
- Delegare il parsing dell'URL a `URLParser`
- Ottenere il controller appropriato tramite `Factory`
- Invocare l'action sul controller
- Inviare la risposta tramite `ResponseStrategy`

## Implementazioni

```mermaid
classDiagram
class Router {
    <<abstract>>
    #factory: Factory
    #urlParser: URLParser
    #responseStrategy: ResponseStrategy
    +dispatch()
    #sendResponse(response: Response)
}

class StandardRouter {
    +dispatch()
}

StandardRouter --|> Router

```

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class Router {
    <<abstract>>
    #factory: Factory
    #urlParser: URLParser
    #responseStrategy: ResponseStrategy
    +dispatch()
}

class Factory {
    +get(className) object
}

class URLParser {
    <<interface>>
    +parse(uri) ParsedURL
}

class ResponseStrategy {
    <<interface>>
    +response(Response)
}

class Response {
    +code: int
    +success: bool
    +jsonData: array
}

class ParsedURL {
    +controller: string
    +action: string
}

class StandardRouter {
    +dispatch()
}

class ControllerType {
    <<enum>>
    AUTH
    RESOURCE
    BOOKING
    +getClass() string
}

Router --> Factory : utilizza
Router --> URLParser : utilizza
Router --> ResponseStrategy : utilizza
Router ..> Response : gestisce
Router --> ParsedURL : usa
StandardRouter --|> Router : estende
Router ..> ControllerType : mappa controller
```

### Relazioni

- **Utilizza**: `Factory` - per la creazione dei controller
- **Utilizza**: `URLParser` - per il parsing URL
- **Utilizza**: `ResponseStrategy` - per l'invio delle risposte
- **Gestisce**: `Response` - oggetto risposta HTTP
- **Mappa**: `ControllerType` enum - converte stringa controller in classe PHP
