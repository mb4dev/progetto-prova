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

## Diagramma di Sequenza

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
    
    Command ->> Service: getAllResourcesByType(TYPE)
    
    alt type == FIELD
        Service ->> FieldsRepo: getAll()
        FieldsRepo ->> DB: query
        DB -->> FieldsRepo: campi rows
        FieldsRepo -->> Service: [{id, sport, price}, ...]
    else type == COURSE
        Service ->> CoursesRepo: getAll()
        CoursesRepo ->> DB: query
        DB -->> CoursesRepo: rows
        CoursesRepo -->> Service: [{id, name, description, price, capacity, schedule: [...]}, ...]
    end
    
    Service -->> Command: resources array
    Command -->> Controller: Response(200, success, data)
    Controller -->> Router: Response
    Router -->> Client: HTTP 200 {resources}
```