
#### Diagramma di sequenza UC01

#####  


``` mermaid 
sequenceDiagram
    autonumber

    actor Utente
    participant GUI as Interfaccia Grafica
    participant API as API REST
    participant Auth as Servizio Autenticazione
    participant DB as Database

    %% LOGIN
    Utente ->> GUI: Inserisce email e password
    GUI ->> API: POST /login (email, password)
    API ->> Auth: autenticaUtente(email, password)
    Auth ->> DB: getUtenteByEmail(email)
    DB -->> Auth: Dati utente + password cifrata

    alt Credenziali valide
        Auth ->> Auth: verificaPassword()
        Auth -->> API: esito positivo + ruolo
        API -->> GUI: 200 OK + token/sessione
        GUI -->> Utente: Mostra home page
    else Credenziali non valide
        Auth -->> API: esito negativo
        API -->> GUI: 401 Unauthorized
        GUI -->> Utente: Messaggio di errore
    end

    %% REGISTRAZIONE
    opt Registrazione nuovo utente
        Utente ->> GUI: Inserisce dati registrazione
        GUI ->> API: POST /register (dati utente)
        API ->> Auth: registraUtente(dati)
        Auth ->> Auth: validaDati()
        Auth ->> Auth: cifraPassword()
        Auth ->> DB: salvaUtente()
        DB -->> Auth: Conferma salvataggio
        Auth -->> API: Registrazione riuscita
        API -->> GUI: 201 Created
        GUI -->> Utente: Accesso al sistema
    end
``` 