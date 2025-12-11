
```mermaid
sequenceDiagram
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Auth as Auth Service
    participant DB as Database

    Utente ->> GUI: Inserisce email e password
    GUI ->> API: POST /auth/login (email, password)
    API ->> API: verificaBody()
    API ->> Auth: autenticaUtente(email, password)
    Auth ->> DB: getUtenteByEmail(email)
    DB -->> Auth: Dati utente + password cifrata

    Auth ->> Auth: verificaPassword(password)
    Auth ->> Auth: generaToken()
    Auth -->> API: token
    API -->> GUI: 200 OK + token
    GUI -->> Utente: Mostra home page
```