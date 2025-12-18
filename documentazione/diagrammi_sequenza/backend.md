``` mermaid 
sequenceDiagram
    autonumber
    
    participant frontend as Frontend
    participant main as Main (PHP Entry Point)
    
    box "Backend System" #f9f9f9
        participant parser as :URLParser
        participant router as :Router
        participant factory as :ControllerFactory
        participant controller as :Controller
        participant service as :Service
        participant repository as :Repository
    end
    participant db as Database
    
    frontend->>+main: HTTP Request

    %% 1. FASE DI INIZIALIZZAZIONE
    main->>+db: <<create connection>>
    db-->>main: connection
    main->>+parser: <<create>>
    parser-->>main: URLParser Instance
    
    main->>+factory: <<create>>(connection)
    factory-->>main: ControllerFactory Instance

    main->>+router: <<create>>(parser, factory)
    router-->>main: Router Instance

    main->>router: dispatch(url)
    
    router->>parser: parse(url)
    parser-->>-router: [controller, action]
    
    router->>factory: create(controller) 
    
    factory->>+repository: <<create>>(connection)
    repository-->>factory: Repository Instance
    
    factory->>+service: <<create>>(repository, dependency)
    service-->>factory: Service Instance
    
    factory->>+controller: <<create>>(service)
    controller-->>factory: Controller Instance
    
    factory-->>-router: Controller Instance

    router->>controller: resolve(action)
    
    controller->>service: action()
    
    service->>repository: findData() / persistData()
    repository->>+db: query/update
    db-->>-repository: Result Data
    repository-->>-service: Entity/Data Object
    
    service->>service: someOperation()
    service-->>-controller: Response Data
    
    controller-->>-router: HTTP Response Object
    router-->>-frontend: Final Response
``` 
