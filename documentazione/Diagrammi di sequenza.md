
#### Diagramma di sequenza UC01

##### Flusso principale - Autenticazione

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


##### Flusso principale - Autenticazione, ERR01 password sbagliata

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
    Auth -->> API: Eccezione: PasswordErrata
    API -->> GUI: 400 BadRequest
    GUI -->> Utente: Mostra errore
```

##### Flusso principale - Autenticazione, ERR02  dati malformati

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
    API -->> GUI: 400 BadRequest
    GUI -->> Utente: Mostra errore
```

##### Flusso principale - Autenticazione, ERR03 utente non trovato

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
    API ->> Auth: login(email, password)
    Auth ->> DB: getUtenteByEmail(email)
    DB -->> Auth: Nessun utente trovato
    Auth -->> API: Eccezione: UtenteNonTrovato
    API -->> GUI: 404 NotFound
    GUI -->> Utente: Mostra errore
```

##### Flusso secondario - Registrazione

``` mermaid 
sequenceDiagram

autonumber
    autonumber
    actor Utente
    participant GUI as GUI
    participant API as RestController
    participant Auth as Auth Service
    participant DB as Database

Utente ->> GUI: Inserisce dati registrazione
GUI ->> API: POST auth/register (dati utente)
API ->> API: verificaBody()
API ->> Auth: registraUtente(dati utente)
Auth ->> Auth: encryptPassword()
Auth ->> DB: salvaUtente()
DB -->> Auth: Conferma salvataggio
Auth ->> Auth: generaToken()
Auth -->> API: token
API -->> GUI: 201 Created + token
GUI -->> Utente: Mostra home page
```




