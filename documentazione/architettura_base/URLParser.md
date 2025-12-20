# URLParser Interface

Questa è l'interfaccia utilizzata nel progetto per il **parsing delle URL** nelle richieste HTTP.

## Descrizione

L'interfaccia `URLParser` definisce le funzioni per estrarre le informazioni necessarie per il routing (controller, action, parametri) da una richiesta HTTP.

## Metodi

```mermaid
classDiagram
class URLParser {
    <<interface>>
    +parse() ParsedURL
}
```

## Utilizzo

Questa interfaccia viene implementata da classi concrete che si occupano di:
- Analizzare l'URL della richiesta HTTP
- Estrarre il controller target
- Estrarre l'action da eseguire
- Estrarre eventuali parametri dalla query string o dal path

## Implementazioni

Le seguenti classi implementano questa interfaccia:

```mermaid
classDiagram
class URLParser {
    <<interface>>
    +parse() ParsedURL
}

class DefaultURLParser {
    -request: array
    +parse() ParsedURL
    -extractController() string
    -extractAction() string
    -extractParams() array
}

DefaultURLParser ..|> URLParser
```

### DefaultURLParser
- **Scopo**: Implementazione standard per parsing di URL tradizionali
- **Utilizzo**: Utilizzato nel routing principale dell'applicazione
- **Formato supportato**: `/controller/action?param=value`

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa interfaccia:

```mermaid
classDiagram
class URLParser {
    <<interface>>
    +parse() ParsedURL
}

class ParsedURL {
    +controller: string
    +action: string
    +params: array
}

class Router {
    -urlParser: URLParser
    +dispatch()
}

class DefaultURLParser {
    +parse() ParsedURL
}

URLParser --> ParsedURL : restituisce
Router --> URLParser : utilizza
DefaultURLParser ..|> URLParser : implementa
```

### Relazioni
- **Utilizzata da**: `Router` - per il parsing delle richieste HTTP
- **Restituisce**: `ParsedURL` - oggetto contenente controller, action e parametri
- **Implementata da**: `DefaultURLParser`

