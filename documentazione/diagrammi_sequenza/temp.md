# Complicato

``` mermaid 

sequenceDiagram
    autonumber
    actor user as Utente
    
    participant frontend as EntryPoint 
    participant view as LoginView
    participant presenter as LoginPresenter
    participant service as APIService
    participant state as AppState
    participant backend as 🖥️ Backend

    activate state
    note over state: Singleton sempre attivo

    frontend ->>+ view : <<create>>
    frontend ->>+ presenter : <<create>>
    presenter ->> state : subscribe(this)
    frontend ->>+ service : <<create>>
    frontend ->> user: Mostra form di login
    
    user ->>+ view : Inserisce credenziali e clicca "Login"
    view ->> view : validateForm()
    view ->>+ presenter: handleLogin(username, password)
    
    alt Input valido
        presenter ->> state : setState({loading: true})
        state -->> presenter : notify()
        presenter ->> view : showLoading()
        
        presenter ->>+ service : login(username, password)
        service ->>+ backend : POST /auth/login
        
        alt Login riuscito (200)
            backend -->>- service : {token, user}
            service ->> service : saveToken(token)
            service ->> state : setState({user, isAuthenticated: true, loading: false})
            service -->>- presenter : {success: true}
            
            state -->> presenter : notify({user, isAuthenticated})
            presenter ->> view : hideLoading()
            presenter ->> view : showSuccess("Login effettuato!")
            presenter ->> frontend : navigate('/home')
            
        else Login fallito (401)
            backend -->>- service : 401 {error: "Invalid credentials"}
            service ->> state : setState({error: "Credenziali errate", loading: false})
            service -->>- presenter : {success: false, error}
            
            state -->> presenter : notify({error})
            presenter ->> view : hideLoading()
            presenter ->> view : showError("Credenziali errate")
        end
        
    else Input non valido
        presenter ->> view : showError("Compila tutti i campi")
    end
    
    deactivate presenter
    deactivate view


```

``` mermaid

sequenceDiagram
    autonumber
    participant frontend as Frontend  
    participant start as Backend Start

    frontend ->> start : POST /auth/login

    participant router as Router
    participant factory as ControllerFactory
    participant parser as URLParser

    start ->>+ parser : <<create>>
    start ->>+ factory : <<create>>

    start ->>+ router : <<create>>(parser, factory)
    router ->> parser : parse(url)
    parser -->>- router : [controller, action]
 
    router ->> factory : create(controller)

    %%factory ->> repository: <<create>>
    participant controller as AuthController
    participant service as AuthService
    participant repo as Repository
    participant db as Database
    
    factory ->>+ db : <<create>>
    db -->> factory : database connection

    factory ->>+ repo : <<create>>(connection)
    repo -->> factory : AuthRepository
    
    factory ->> service : <<create>>(repository)
    service -->> factory: AuthService
    
    factory ->> controller : <<create>>
    controller -->> factory : AuthController
    factory -->>- router : AuthController
    router ->> controller: resolveAction(action)

    controller ->> controller : getBody()
    controller ->> service : login(body)

    service ->> service : verifyBody(body)
    service ->> repo: getUserByEmail(email)
    repo ->> db: query
    repo -->> service : user
    service ->> service : password_hash(password)
    service ->> service: verify_password(user.password, password_hash)
    service -->> service : generateToken()
    service -->> controller: true
    controller -->> Router : Response({code: 200, success: true, token})
    router -->>- frontend: response


    ```
    
    
    ``` mermaid 
sequenceDiagram
    autonumber
    participant Frontend
    participant Router
    participant AuthController
    participant AuthService
    participant AuthRepository
    participant Database

    Note over Router,Database: Inizializzazione componenti (Factory pattern)

    Frontend->>+Router: POST /auth/login
    Router->>Router: parse URL
    Router->>+AuthController: login(request)
    
    AuthController->>AuthController: getBody()
    AuthController->>+AuthService: login(email, password)
    
    AuthService->>AuthService: verifyBody()
    AuthService->>+AuthRepository: getUserByEmail(email)
    AuthRepository->>+Database: SELECT * FROM users WHERE email = ?
    Database-->>-AuthRepository: user data
    AuthRepository-->>-AuthService: user
    
    AuthService->>AuthService: verify password hash
    AuthService->>AuthService: generateToken()
    AuthService-->>-AuthController: token
    
    AuthController-->>-Router: Response(200, {success: true, token})
    Router-->>-Frontend: JSON response

    ``` 

``` mermaid 

sequenceDiagram
    autonumber
    
    %% Attori principali dell'avvio
    participant main as Main / Bootstrap
    participant router as Router
    
    %% Il "Costruttore"
    participant factory as ControllerFactory

    %% Le Dipendenze da creare
    participant db as Database
    participant repo as AuthRepository
    participant service as AuthService
    participant ctrl as AuthController

    Note over main, ctrl: Fase 1: Inizializzazione Infrastruttura
    main->>+router: <<create>> (parser)
    main->>+factory: <<create>>
    
    Note over router, ctrl: Fase 2: Risoluzione delle Dipendenze (Wiring)
    
    %% Simuliamo che il Router chieda il controller (Lazy loading o Startup)
    router->>+factory: getController('Auth')


        note right of factory: Dependency Injection Chain
        
        %% 1. Database (Livello più basso)
        factory->>+db: <<connect>>
        db-->>-factory: db connection

        %% 2. Repository (Dipende da DB)
        factory->>+repo: <<create>> (db connection)
        repo-->>-factory: AuthRepository instance

        %% 3. Service (Dipende da Repository)
        factory->>+service: <<create>> (AuthRepository)
        service-->>-factory: AuthService instance

        %% 4. Controller (Dipende da Service)
        factory->>+ctrl: <<create>> (AuthService)
        ctrl-->>-factory: AuthController instance
    

    factory-->>-router: AuthController (pronto all'uso)

    ``` 

La Sinergia Perfetta
Quando un developer deve lavorare sulla Login, seguirà questa logica con i tuoi diagrammi:

"Come si avvia la mia classe?"

Legge il Diagramma di Sequenza Generico. Scopre che il Router chiama la Factory per ottenere il Controller.

"Cosa deve creare la Factory?"

Consulta il Diagramma delle Classi. Scopre che la AuthController ha bisogno di AuthService, che ha bisogno di AuthRepository, ecc. Questo è il Setup di cui abbiamo parlato.

"Qual è la logica di business?"

Legge il Diagramma di Sequenza specifico per la Login. Vede i passaggi operativi: verifica, query al DB, hash password e generazione del token.

In sintesi: il Diagramma di Sequenza Generico spiega come funziona la tua infrastruttura, e il Diagramma delle Classi spiega cosa iniettare durante quel processo. È una strategia di documentazione completa ed efficace.

