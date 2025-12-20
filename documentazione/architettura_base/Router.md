# Router (Classe Astratta)

Questa è la classe astratta utilizzata nel progetto per il **routing delle richieste HTTP** verso i controller appropriati.

## Descrizione

La classe astratta `Router` definisce la struttura base per gestire il routing delle richieste HTTP. Coordina il parsing dell'URL, la creazione del controller e l'invio della risposta.

## Struttura

```mermaid
classDiagram
class Router {
    <<abstract>>
    #urlParser: URLParser
    #controllerFactory: ControllerFactory
    #responseStrategy: ResponseStrategy
    +dispatch() 
    #sendResponse(response: Response) 
}
```

## Responsabilità

- Ricevere le richieste HTTP
- Delegare il parsing dell'URL a `URLParser`
- Ottenere il controller appropriato tramite `ControllerFactory`
- Invocare l'action sul controller
- Inviare la risposta tramite `ResponseStrategy`

## Implementazioni

Le seguenti classi estendono questa classe astratta:

```mermaid
classDiagram
class Router {
    <<abstract>>
    #urlParser: URLParser
    #controllerFactory: ControllerFactory
    #responseStrategy: ResponseStrategy
    +dispatch()
    #sendResponse(response: Response)
}

class DefaultRouter {
    +dispatch()
    -handleRequest()
    -routeToController(parsedURL: ParsedURL)
}

DefaultRouter --|> Router

```

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class Router {
    <<abstract>>
    #urlParser: URLParser
    #controllerFactory: ControllerFactory
    #responseStrategy: ResponseStrategy
    +dispatch()
}

class URLParser {
    <<interface>>
}

class ControllerFactory {
    +create(type: string) Controller
}

class ResponseStrategy {
    <<interface>>
}

class Response {
    +code: int
    +success: bool
    +data: mixed
}

class DefaultRouter {
    +dispatch()
}

Router --> URLParser : utilizza
Router --> ControllerFactory : utilizza
Router --> ResponseStrategy : utilizza
Router ..> Response : gestisce
DefaultRouter --|> Router : estende
```

### Relazioni

- **Utilizza**: `URLParser` - per il parsing delle URL
- **Utilizza**: `ControllerFactory` - per la creazione dei controller
- **Utilizza**: `ResponseStrategy` - per l'invio delle risposte
- **Gestisce**: `Response` - oggetto risposta HTTP
