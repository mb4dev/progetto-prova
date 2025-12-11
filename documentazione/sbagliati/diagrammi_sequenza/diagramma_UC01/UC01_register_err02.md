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
DB -->> Auth:  Utente trovato
Auth -->> API: Eccezione: UtenteGiaRegistrato
API -->> GUI: 400 BadRequest
GUI -->> Utente: Mostra errore


```