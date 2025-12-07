## Attori

- **Utente non autenticato**
    - Può registrarsi o autenticarsi
        
- **Cliente**
    - Utente autenticato 
    - Può prenotare campi, iscriversi a corsi, effettuare pagamenti e acquistare abbonamenti
        
- **Admin**
    - Utente autenticato con ruolo amministrativo
### Identificazione casi d'uso

- **UC01** – Registrazione / Autenticazione
- **UC02** – Visualizzare e gestire profilo utente
- **UC03** – Visualizzare disponibilità campi
- **UC04** – Prenotare campo sportivo
- **UC05** – Visualizzare corsi
- **UC06** – Iscriversi a una lezione di un corso
- **UC07** – Cancellare prenotazione
- **UC08** – Effettuare pagamento singolo
- **UC09** – Acquistare abbonamento
- **UC10** – Visualizzare storico prenotazioni e pagamenti
- **UC11** – Gestire campi sportivi (Admin)
- **UC12** – Gestire corsi e lezioni (Admin)
- **UC13** – Gestire tariffe e abbonamenti (Admin)
### Descrizione dettagliata casi d'uso 

| **Caso d'uso**                         | Registrazione/Autenticazione                                                                                                                                                                                                                                                   |
| -------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **ID**                                 | UC01                                                                                                                                                                                                                                                                           |
| **Descrizione**                        | Consente all'utente di registrarsi o di accedere al sistema                                                                                                                                                                                                                    |
| **Attore principale**                  | Utente non autenticato                                                                                                                                                                                                                                                         |
| **Attore secondario**                  | Sistema                                                                                                                                                                                                                                                                        |
| **Precondizioni**                      | 1. L'utente non è autenticato                                                                                                                                                                                                                                                  |
| **Postcondizioni**                     | 1. L'utente è autenticato nel sistema con il proprio ruolo<br>2. Viene mostrata la pagina principale del sistema                                                                                                                                                               |
| **Flusso principale** - Autenticazione | 1. L'utente selezione "Login"<br>2. Il sistema richiede email e password<br>3. L'utente inserisce le credenziali<br>4. Il sistema verifica le credenziali<br>5. Il sistema autentica l'utente e mostra la pagina principale                                                    |
| **Flusso secondario** - Registrazione  | 1. L'utente seleziona "Registrazione"<br>2. Il sistema richiede i dati anagrafici, email e password  <br>3. L'utente inserisce i dati richiesti<br>4. Il sistema valida i dati<br>5. Il sistema crea il nuovo account, conferma la registrazione e mostra la pagina principale |

| **Caso d'uso**        | Gestione profilo utente                                                                                                                                                                                             |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC02                                                                                                                                                                                                                |
| **Descrizione**       | L'utente visualizza e gestisce i propri dati personali                                                                                                                                                              |
| **Attore principale** | Utente                                                                                                                                                                                                              |
| **Attore secondario** | Sistema                                                                                                                                                                                                             |
| **Precondizioni**     | 1. Utente autenticato                                                                                                                                                                                               |
| **Postcondizioni**    | 1. I dati del profilo sono visualizzati                                                                                                                                                                             |
| **Flusso principale** | 1. L'utente accede alla sezione profilo.<br>2. Il sistema mostra i dati attuali.<br>3. L'utente può modificare uno o più campi.<br>4. L'utente conferma le modifiche.<br>5. Il sistema valida e salva i nuovi dati. |
| **Flusso secondario** |                                                                                                                                                                                                                     |

| **Caso d'uso**        | Visualizzazione disponibilità                                                                                                                                                                                                                  |
| --------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC03                                                                                                                                                                                                                                           |
| **Descrizione**       | L'utente visualizza gli slot temporali liberi per i campi sportivi                                                                                                                                                                             |
| **Attore principale** | Utente                                                                                                                                                                                                                                         |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                        |
| **Precondizioni**     | 1. L'utente è autenticato                                                                                                                                                                                                                      |
| **Postcondizioni**    | 1. Viene mostra un calendario con gli slot temporali disponibili                                                                                                                                                                               |
| **Flusso principale** | 1. L'utente seleziona lo sport<br>2. Il sistema interroga il database per gli slot occupati<br>3. Il sistema mostra un calendario settimanale con gli slot temporali disponibili<br>4. L'utente eventualmente può mandare avanti il calendario |
| **Flusso secondario** |                                                                                                                                                                                                                                                |

| **Caso d'uso**        | Prenotazione campo sportivo                                                                                                                                                                                                                                                                                                |
| --------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC04                                                                                                                                                                                                                                                                                                                       |
| **Descrizione**       | L'utente prenota un campo selezionando uno slot temporale di almeno 30 minuti                                                                                                                                                                                                                                              |
| **Attore principale** | Utente                                                                                                                                                                                                                                                                                                                     |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                                                                                                    |
| **Precondizioni**     | 1. L'utente è autenticato<br>2. Slot selezionato disponibile                                                                                                                                                                                                                                                               |
| **Postcondizioni**    | 1. Prenotazione registrata nel sistema<br>2. Campo risulta occupato per quello slot                                                                                                                                                                                                                                        |
| **Flusso principale** | 1. L'utente visualizza la disponibilità (**UC03**)<br>2. Seleziona uno slot libero<br>3. Il sistema blocca temporaneamente lo slot<br>4. L'utente effettua il pagamento<br>5. Il sistema conferma la prenotazione                                                                                                          |
| **Flusso secondario** | Flusso secondario A - Slot non disponibile<br>1. Il sistema rileva che lo slot non è più disponibile<br>2. Viene mostrato un messaggio di errore <br>3. La disponibilità viene aggiornata<br><br>Flusso secondario B - Errore pagamento <br>1. Il pagamento non va a buon fine <br>2. Il sistema libera lo slot temporaneo |

| **Caso d'uso**        | Visualizzazione corsi                                                                                                                    |
| --------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC05                                                                                                                                     |
| **Descrizione**       | L'utente consulta l'elenco dei corsi disponibili                                                                                         |
| **Attore principale** | Utente                                                                                                                                   |
| **Attore secondario** | Sistema                                                                                                                                  |
| **Precondizioni**     | 1. Utente autenticato                                                                                                                    |
| **Postcondizioni**    | 1. Viene mostrato l'elenco dei corsi disponibili                                                                                         |
| **Flusso principale** | 1. L'utente accede alla sezione corsi<br>2. Il sistema mostra l'elenco dei corsi attivi con dettagli (orario, posti disponibili, prezzo) |
| **Flusso secondario** |                                                                                                                                          |

| **Caso d'uso**        |     |
| --------------------- | --- |
| **ID**                |     |
| **Descrizione**       |     |
| **Attore principale** |     |
| **Attore secondario** |     |
| **Precondizioni**     |     |
| **Postcondizioni**    |     |
| **Flusso principale** |     |
| **Flusso secondario** |     |

| **Caso d'uso**        |     |
| --------------------- | --- |
| **ID**                |     |
| **Descrizione**       |     |
| **Attore principale** |     |
| **Attore secondario** |     |
| **Precondizioni**     |     |
| **Postcondizioni**    |     |
| **Flusso principale** |     |
| **Flusso secondario** |     |

| **Caso d'uso**        |     |
| --------------------- | --- |
| **ID**                |     |
| **Descrizione**       |     |
| **Attore principale** |     |
| **Attore secondario** |     |
| **Precondizioni**     |     |
| **Postcondizioni**    |     |
| **Flusso principale** |     |
| **Flusso secondario** |     |

| **Caso d'uso**        |     |
| --------------------- | --- |
| **ID**                |     |
| **Descrizione**       |     |
| **Attore principale** |     |
| **Attore secondario** |     |
| **Precondizioni**     |     |
| **Postcondizioni**    |     |
| **Flusso principale** |     |
| **Flusso secondario** |     |

| **Caso d'uso**        |     |
| --------------------- | --- |
| **ID**                |     |
| **Descrizione**       |     |
| **Attore principale** |     |
| **Attore secondario** |     |
| **Precondizioni**     |     |
| **Postcondizioni**    |     |
| **Flusso principale** |     |
| **Flusso secondario** |     |

| **Caso d'uso**        |     |
| --------------------- | --- |
| **ID**                |     |
| **Descrizione**       |     |
| **Attore principale** |     |
| **Attore secondario** |     |
| **Precondizioni**     |     |
| **Postcondizioni**    |     |
| **Flusso principale** |     |
| **Flusso secondario** |     |

| **Caso d'uso**        |     |
| --------------------- | --- |
| **ID**                |     |
| **Descrizione**       |     |
| **Attore principale** |     |
| **Attore secondario** |     |
| **Precondizioni**     |     |
| **Postcondizioni**    |     |
| **Flusso principale** |     |
| **Flusso secondario** |     |