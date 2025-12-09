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