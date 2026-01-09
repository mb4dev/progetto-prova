# Modulo Selezione Sport - Backend

## Casi d'uso Correlati
- **UC03**: Visualizzazione Disponibilità Campi (Selezione Sport iniziale)

Questo modulo gestisce il recupero delle informazioni sugli sport disponibili.

## Diagramma delle Classi

### Controller Layer

```mermaid
classDiagram
class Controller {
    <<Controller>>
}

class SportController {
    -sportService: SportService
    +getSports(req: Request)
}

SportController --|> Controller
```

## Diagrammi di Sequenza

### Flusso Recupero Sport (Backend) (Derived)

```mermaid
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant router as Router
    participant controller as :SportController
    participant service as :SportService
    participant repository as :SportRepository
    participant db as Database

    frontend ->>+ router: GET /sports
    router ->>+ controller: resolveAction()
    controller ->>+ service: getAllSports()
    service ->>+ repository: findAll()
    repository ->>+ db: query
    db -->>- repository: records
    repository -->>- service: Sport[]
    service -->>- controller: Response(200, success, sports)
    controller -->>- router: Response
    router -->>- frontend: HTTP Response
```
