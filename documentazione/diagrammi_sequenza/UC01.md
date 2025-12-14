#
## Diagramma di sequenza per caso d'uso UC01 - caso login
### Frontend
``` mermaid 
sequenceDiagram
    autonumber
    actor user as Utente
    
    participant view as LoginView
    participant presenter as LoginPresenter
    participant api as APIService
    participant backend as Backend

    user ->>+ view : Inserisce credenziali
    view ->>+ presenter:  new LoginEvent(username, password)
    
    presenter ->>+ api : login(username, password)
    api ->>+ backend : POST /auth/login
    alt Response 200
        backend -->> api : {success: true, token, user}
        api ->> api : saveToken(token)
        api -->> presenter : {success: true, user}
        presenter ->> view : display("success")
        presenter ->> user : redirect('/home')
    else Response 401/404/500
        backend -->>- api : {success: false, message}
        api -->>- presenter : {success: false, message}
        presenter ->>- view : display(message)
    end

    deactivate view 

```

### Backend
``` mermaid 
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant main as Main
    participant router as Router
    participant controller as AuthController
    participant service as DefaultAuthService
    participant repository as DefaultAuthRepository
    participant validator as DefaultPasswordValidator
    participant tokenManager as DefaultJwtTokenManager
    participant db as Database

    frontend ->>+ main: HTTP POST /auth/login
    main ->>+ router: dispatch(url)
    router ->>+ controller: resolve(action)
    controller ->> controller: getBody() 
    alt body malformato
        controller -->> router: Response{code:400, success:false, message:"Body malformato"}
        router -->> frontend: Final Response
    else corpo corretto
        controller ->> service: login(User(email,password))
        service ->> repository: findByEmail(email)
        repository ->>+ db: query()
        db -->>- repository: User|null
        repository -->> service: User|null
        alt utente non trovato
            service -->> controller: Response{code:404, success:false, message:"Utente non trovato"}
            controller -->> router: Response
            router -->> frontend: Final Response
        else utente trovato
            service ->> validator: validate(password, User.password)
            alt password errata
                validator -->> service: false
                service -->> controller: Response{code:401, success:false, message:"Password errata"}
                controller -->> router: Response
                router -->> frontend: Final Response
            else password corretta
                validator -->> service: true
                service ->> tokenManager: encode(User)
                tokenManager -->> service: token
                service -->> controller: Response{code:200, success:true, user, token}
                controller ->> controller: setCookie(token)
                controller -->> router: Response
                router -->> frontend: Final Response
            end
        end
    end

```

## Diagramma delle classi per caso d'uso UC01 - caso register


``` mermaid 
sequenceDiagram
    autonumber
    actor user as Utente
    
    participant view as RegisterView
    participant presenter as RegisterPresenter
    participant api as APIService
    participant backend as Backend

    user ->>+ view : Inserisce credenziali
    view ->>+ presenter:  emette RegisterEvent(email, username, password)
    
    presenter ->>+ api : register(email, username, password)
    api ->>+ backend : POST /auth/register
    alt Response 200
        backend -->> api : {success: true, token, user}
        api ->> api : saveToken(token)
        api -->> presenter : {success: true, user}
        presenter ->> view : display("success")
        presenter ->> user : redirect('/home')
    else Response 401/404/500
        backend -->>- api : {success: false, message}
        api -->>- presenter : {success: false, message}
        presenter ->>- view : display(message)
    end

    deactivate view 
```

### Backend
``` mermaid 
sequenceDiagram
    autonumber

    participant frontend as Frontend
    participant main as Main
    participant router as Router
    participant controller as AuthController
    participant service as DefaultAuthService
    participant repository as DefaultAuthRepository
    participant validator as DefaultPasswordValidator
    participant tokenManager as DefaultJwtTokenManager
    participant db as Database

    frontend ->>+ main: HTTP POST /auth/register
    main ->>+ router: dispatch(url)
    router ->>+ controller: resolve(action)
    controller ->> controller: getBody() 
    alt body malformato
        controller -->> router: Response{code:400, success:false, message:"Body malformato"}
        router -->> frontend: Final Response
    else corpo corretto
        controller ->> service: register(User(email,name,password))
        service ->> repository: findByEmail(email)
        repository ->>+ db: query()
        db -->>- repository: User|null
        repository -->> service: User|null
        alt utente già registrato
            service -->> controller: Response{code:409, success:false, message:"Utente già registrato"}
            controller -->> router: Response
            router -->> frontend: Final Response
        else utente non registrato
            service ->> validator: validate(password)  %% opzionale hash/validazione
            service ->> repository: insertUser(User)
            repository ->>+ db: insert()
            db -->>- repository: ok/error
            alt errore DB
                repository -->> service: Response{code:500, success:false, message:"Errore interno database"}
                service -->> controller: Response
                controller -->> router: Response
                router -->> frontend: Final Response
            else registrazione avvenuta
                repository -->> service: ok
                service ->> tokenManager: encode(User)
                tokenManager -->> service: token
                service -->> controller: Response{code:200, success:true, user, token}
                controller ->> controller: setCookie(token)
                controller -->> router: Response
                router -->> frontend: Final Response
            end
        end
    end

```