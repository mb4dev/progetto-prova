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
Auth ->> DB: getUtenteByEmail(email)
DB -->> Auth:  Nessun utente trovato
Auth ->> Auth: encryptPassword()
Auth ->> DB: salvaUtente(utente)
DB -->> Auth: Errore salvataggio
Auth -->> API: Eccezione: ErroreSalvataggio
API -->> GUI: 500 InternalServerError
GUI -->> Utente: Mostra errore
    

```