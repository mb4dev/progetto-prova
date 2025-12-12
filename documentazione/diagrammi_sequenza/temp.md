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