# Modulo resources - Backend

## Panoramica

Questo modulo gestisce le risorse del centro sportivo, inclusi campi sportivi e corsi. Fornisce operazioni di lettura per ottenere tutti i resource o filtrare per tipo.

## Diagramma delle Classi

```mermaid
classDiagram
    class ResourceController {
        -service: ResourceService
        -authMiddleware: HttpSecurity
        #registerCommands()
    }

    class GetAllResourceCommand {
        -service: ResourceService
        +execute(body, query)
    }

    class ResourceService {
        <<interface>>
        +getAllResourcesByType(type: ResourceType) array
    }

    class StandardResourceService {
        -fieldsRepository: ResourcesRepository
        -coursesRepository: ResourcesRepository
        +getAllResourcesByType(type)
    }

    class ResourcesRepository {
        <<interface>>
        +getAll() array
        +getResourceById(id) array
    }

    class PostgreFieldsRepository {
        -db: PDO
        +getAll()
        +getResourceById(id)
    }

    class PostgreCoursesRepository {
        -db: PDO
        +getAll()
        +getResourceById(id)
    }

    class ResourceType {
        <<enum>>
        FIELD
        COURSE
    }

    class HttpSecurity {
        <<interface>>
        +authenticate(token) User
    }

    class AuthMiddleware {
        -tokenService: TokenService
        -authRepository: AuthRepository
        +authenticate(token)
    }

    ResourceController --> GetAllResourceCommand : crea
    GetAllResourceCommand --> ResourceService
    StandardResourceService ..|> ResourceService
    StandardResourceService --> ResourcesRepository
    PostgreFieldsRepository ..|> ResourcesRepository
    PostgreCoursesRepository ..|> ResourcesRepository
    ResourceController --> HttpSecurity
    AuthMiddleware ..|> HttpSecurity
```

## Flusso di Lettura Resource (Diagramma di Sequenza)

```mermaid
sequenceDiagram
    autonumber
    participant Client
    participant Router as StandardRouter
    participant Controller as ResourceController
    participant Command as GetAllResourceCommand
    participant Service as StandardResourceService
    participant FieldsRepo as PostgreFieldsRepository
    participant CoursesRepo as PostgreCoursesRepository
    participant DB as PostgreSQL

    Client ->> Router: GET /resource?type=field
    Router ->> Controller: resolveAction("")
    
    Controller ->> Command: execute(body, query)
    
    Note over Command: Estrai type dai query params
    
    Command ->> Service: getAllResourcesByType(FIELD)
    
    alt type == FIELD
        Service ->> FieldsRepo: getAll()
        FieldsRepo ->> DB: SELECT * FROM centro_sportivo.campi
        DB -->> FieldsRepo: campi rows
        FieldsRepo -->> Service: [{id, sport, price}, ...]
    else type == COURSE
        Service ->> CoursesRepo: getAll()
        CoursesRepo ->> DB: SELECT c.*, oc.orario FROM centro_sportivo.corsi c...
        DB -->> CoursesRepo: corsi rows with orari
        CoursesRepo -->> Service: [{id, name, description, price, capacity, schedule: [...]}, ...]
    end
    
    Service -->> Command: resources array
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {resources}
```

## Endpoint

| Metodo | Path | Descrizione |
|--------|------|-------------|
| GET | `/resource` | Ottiene tutti i resource |
| GET | `/resource?type=field` | Ottiene solo i campi sportivi |
| GET | `/resource?type=course` | Ottiene solo i corsi |

## Comandi

### GetAllResourceCommand

```php
class GetAllResourceCommand extends Command {
    public function __construct(private ResourceService $service) {}
    
    public function execute(array $params, array $query = []): Response {
        $type = $query['type'] ?? null;
        
        if ($type === null) {
            // Return all resources merged
            $fields = $this->service->getAllResourcesByType(ResourceType::FIELD);
            $courses = $this->service->getAllResourcesByType(ResourceType::COURSE);
            $result = array_merge($fields, $courses);
        } else {
            $resourceType = ResourceType::from($type);
            $result = $this->service->getAllResourcesByType($resourceType);
        }
        
        return new Response(200, true, $result);
    }
    
    public function getRequiredBodyParameters(): array { return []; }
    public function getRequiredQueryParameters(): array { return []; }
    public function getRequiredHttpMethod(): string { return 'get'; }
    public function requiresAuthentication(): bool { return false; }
    public function getRequiredRoles(): array { return []; }
}
```

## ResourceType Enum

```mermaid
classDiagram
    class ResourceType {
        <<enum>>
        FIELD
        COURSE
    }
```

### Struttura Dati

**Campi (Fields):**
```json
{
  "id": 1,
  "sport": "calcio",
  "price": 50.00
}
```

**Corsi (Courses):**
```json
{
  "id": 1,
  "name": "Corso Padel Base",
  "description": "Introduzione al padel",
  "price": 90.00,
  "capacity": 18,
  "schedule": ["09:00", "11:00", "15:00"]
}
```

## Dipendenze del Modulo

```mermaid
classDiagram
    class ResourceController {
        <<depends on>>
    }
    class ResourceService {
        <<depends on>>
    }
    class ResourcesRepository {
        <<depends on>>
    }
    class ResourceType {
        <<depends on>>
    }
    class HttpSecurity {
        <<depends on>>
    }
```

## Note

- Il modulo utilizza **PostgreSQL** come database
- L'endpoint è **pubblico** (nessuna autenticazione richiesta)
- I corsi includono gli orari dalla tabella `orari_corsi`
- I campi sono semplici record dalla tabella `campi`
- Il parametro `type` nei query params è opzionale