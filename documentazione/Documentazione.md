### Sommario

- [Sommario](#sommario)
- [Introduzione](#introduzione)
	- [Gestione CUS PARMA](#gestione-cus-parma)
- [Analisi requisiti](#analisi-requisiti)
	- [Requisiti Funzionali](#requisiti-funzionali)
	- [Requisiti non funzionali](#requisiti-non-funzionali)
	- [Dipendenze tra requisiti](#dipendenze-tra-requisiti)
- [Casi d'uso](#casi-duso)
	- [Attori](#attori)
	- [Identificazione casi d'uso](#identificazione-casi-duso)
	- [Descrizione dettagliata casi d'uso](#descrizione-dettagliata-casi-duso)
		- [UC01](#uc01)
		- [UC02](#uc02)
		- [UC03](#uc03)
		- [UC04](#uc04)
		- [UC05](#uc05)
		- [UC06](#uc06)
		- [UC07](#uc07)
		- [UC08](#uc08)
		- [UC09](#uc09)
		- [UC10](#uc10)
		- [UC11](#uc11)
		- [UC12](#uc12)
		- [UC13](#uc13)


### Introduzione 

#### Gestione CUS PARMA
Sistema semplificato per la prenotazione di campi (es. tennis, calcetto)
o corsi (es. palestra) presso il CUS. Pagamenti (simulati), gestione abbonamenti vs pagamenti
singoli, orari e disponibilità risorse (campi/istruttori).

--- 

### Analisi requisiti
#### Requisiti Funzionali

- **RF01. Registrazione/Autenticazione** - L'utente deve potersi registrare con email e password

- **RF02. Gestione profilo utente** - L'utente può visualizzare dati personali.

- **RF03. Visualizzazione storico prenotazioni e ordini** - L’utente può visualizzare lo storico delle prenotazioni effettuate, dei pagamenti e lo stato degli eventuali abbonamenti.

- **RF04. Ruoli** - Il sistema deve distinguere tra: 
	- **Cliente**: prenotazione campi, iscrizione ai corsi, pagamenti
	- **Admin**: gestione campi sportivi, corsi, utenti e tariffe

- **RF05. Visualizzazione disponibilità campo** - Il sistema deve mostrare gli slot temporali disponibili per la prenotazione del campo sportivo

- **RF06. Prenotazione campo sportivo** - Il cliente può prenotare un campo sportivo selezionando slot temporali con durata minima di 30 minuti.

- **RF07. Cancellazione prenotazione** - Il cliente può cancellare una prenotazione fino a 24 ore prima dell’orario previsto.

- **RF08. Visualizzazione corsi** - Il sistema mostra l’elenco dei corsi e delle singole lezioni disponibili

- **RF09. Iscrizione a un corso** - L'utente, se nel corso c'è ancora spazio disponibile, può iscriversi per la singola lezione

- **RF10. Pagamento singolo** - Il sistema deve consentire il pagamento di una prenotazione o di una lezione senza utilizzo di abbonamento.

- **RF11. Gestione abbonamenti** - Il sistema deve consentire l’acquisto di abbonamenti mensili, trimestrali o annuali che permettono un numero definito di ingressi ai corsi

- **RF12. Memorizzazione pagamenti** - Il sistema deve mantenere in memoria eventuali pagamenti/abbonamenti 

#### Requisiti non funzionali

- **RNF01** - Password memorizzare in forma cifrata
- **RNF02** - Accesso alle funzionalità basato su ruoli
- **RNF03** - Interfaccia web responsive
- **RNF04** - Nessuna doppia prenotazione della stessa risorsa

#### Dipendenze tra requisiti

| Requisito                                               | Dipende da                         | Descrizione della dipendenza                                           |
| ------------------------------------------------------- | ---------------------------------- | ---------------------------------------------------------------------- |
| **RF01. Registrazione/Autenticazione**                  |                                    | Requisito base per l'accesso al sistema                                |
| **RF02. Gestione profilo utente**                       | RF01                               | L'utente deve essere autenticato                                       |
| **RF03. Visualizzazione storico prenotazioni e ordini** | RF01                               | L'utente deve essere autenticato                                       |
| **RF04. Ruoli**                                         | RF01                               | I ruoli sono associati ad utenti autenticati                           |
| **RF05. Visualizzazione disponibilità campo**           | RF01                               | Funzione disponibile solo per utenti autenticati                       |
| **RF06. Prenotazione campo sportivo**                   | RF01, RF04, RF05                   | Richiede autenticazione, ruolo Cliente e visualizzazione disponibilità |
| **RF07. Cancellazione prenotazione**                    | RF01, RF06                         | L’utente deve essere autenticato e avere una prenotazione esistente    |
| **RF08. Visualizzazione corsi**                         | RF01                               | Accesso consentito agli utenti autenticati                             |
| **RF09. Iscrizione a un corso**                         | RF01, RF04, RF08                   | Richiede autenticazione, ruolo Cliente e visualizzazione corsi         |
| **RF10. Pagamento singolo**                             | RF01, RF06, RF09                   | Il pagamento è associato a una prenotazione o a un’iscrizione          |
| **RF11. Gestione abbonamenti**                          | RF01, RF04                         | Accessibile solo a utenti autenticati con ruolo Cliente                |
| **RF12. Memorizzazione pagamenti**                      | RRF10, RF11                        | Deve essere effettuato un pagamento                                    |
|                                                         |                                    |                                                                        |
| **RNF01**                                               | RF01                               |                                                                        |
| **RNF02**                                               | RF04, RF06, RF07, RF09, RF10, RF11 |                                                                        |
| **RNF03**                                               | Tutti i requisiti funzionali       |                                                                        |
| **RNF04**                                               | RF05, RF06, RF07                   |                                                                        |

---

### Casi d'uso

#### Attori

- **Utente non autenticato**
    - Può registrarsi o autenticarsi
        
- **Utente**
    - Utente autenticato 
    - Può prenotare campi, iscriversi a corsi, effettuare pagamenti e acquistare abbonamenti
        
- **Admin**
    - Utente autenticato con ruolo amministrativo
#### Identificazione casi d'uso

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

#### Descrizione dettagliata casi d'uso 

##### UC01



| **Caso d'uso**        | Registrazione/Autenticazione                                                                                                                                                                                                                                                                                                 |
| --------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC01                                                                                                                                                                                                                                                                                                                         |
| **Descrizione**       | Consente all'utente di registrarsi o di accedere al sistema                                                                                                                                                                                                                                                                  |
| **Attore principale** | Utente non autenticato                                                                                                                                                                                                                                                                                                       |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                                                                                                      |
| **Precondizioni**     | 1. L'utente non è autenticato                                                                                                                                                                                                                                                                                                |
| **Postcondizioni**    | 1. L'utente è autenticato nel sistema con il proprio ruolo.<br>2. Viene mostrata la pagina principale del sistema.                                                                                                                                                                                                           |
| **Flusso principale** | 1. L'utente selezione "Login".<br>2. Il sistema richiede email e password.<br>3. L'utente inserisce le credenziali.<br>4. Il sistema verifica le credenziali.<br>5. Il sistema autentica l'utente e mostra la pagina principale.                                                                                             |
| **Flusso secondario** | **Flusso secondario A - Registrazione**<br>1. L'utente seleziona "Registrazione".<br>2. Il sistema richiede i dati anagrafici, email e password.<br>3. L'utente inserisce i dati richiesti.<br>4. Il sistema valida i dati.<br>5. Il sistema crea il nuovo account, conferma la registrazione e mostra la pagina principale. |
##### UC02

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
##### UC03

| **Caso d'uso**        | Visualizzazione disponibilità                                                                                                                                                                                                                      |
| --------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC03                                                                                                                                                                                                                                               |
| **Descrizione**       | L'utente visualizza gli slot temporali liberi per i campi sportivi                                                                                                                                                                                 |
| **Attore principale** | Utente                                                                                                                                                                                                                                             |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                            |
| **Precondizioni**     | 1. L'utente è autenticato.                                                                                                                                                                                                                         |
| **Postcondizioni**    | 1. Viene mostra un calendario con gli slot temporali disponibili.                                                                                                                                                                                  |
| **Flusso principale** | 1. L'utente seleziona lo sport.<br>2. Il sistema interroga il database per gli slot occupati.<br>3. Il sistema mostra un calendario settimanale con gli slot temporali disponibili.<br>4. L'utente eventualmente può mandare avanti il calendario. |
| **Flusso secondario** |                                                                                                                                                                                                                                                    |
##### UC04

| **Caso d'uso**        | Prenotazione campo sportivo                                                                                                                                                                                                                                                                                                            |
| --------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC04                                                                                                                                                                                                                                                                                                                                   |
| **Descrizione**       | L'utente prenota un campo selezionando uno slot temporale di almeno 30 minuti                                                                                                                                                                                                                                                          |
| **Attore principale** | Utente                                                                                                                                                                                                                                                                                                                                 |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                                                                                                                |
| **Precondizioni**     | 1. L'utente è autenticato.<br>2. Slot selezionato disponibile.                                                                                                                                                                                                                                                                         |
| **Postcondizioni**    | 1. Prenotazione registrata nel sistema.<br>2. Campo risulta occupato per quello slot.                                                                                                                                                                                                                                                  |
| **Flusso principale** | 1. L'utente visualizza la disponibilità (**UC03**).<br>2. Seleziona uno slot libero.<br>3. Il sistema blocca temporaneamente lo slot.<br>4. L'utente effettua il pagamento.<br>5. Il sistema conferma la prenotazione.                                                                                                                 |
| **Flusso secondario** | **Flusso secondario A - Slot non disponibile**<br>1. Il sistema rileva che lo slot non è più disponibile.<br>2. Viene mostrato un messaggio di errore .<br>3. La disponibilità viene aggiornata.<br><br>**Flusso secondario B - Errore pagamento**<br>1. Il pagamento non va a buon fine. <br>2. Il sistema libera lo slot temporaneo. |
##### UC05

| **Caso d'uso**        | Visualizzazione corsi                                                                                                                      |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------ |
| **ID**                | UC05                                                                                                                                       |
| **Descrizione**       | L'utente consulta l'elenco dei corsi disponibili                                                                                           |
| **Attore principale** | Utente                                                                                                                                     |
| **Attore secondario** | Sistema                                                                                                                                    |
| **Precondizioni**     | 1. Utente autenticato.                                                                                                                     |
| **Postcondizioni**    | 1. Viene mostrato l'elenco dei corsi disponibili.                                                                                          |
| **Flusso principale** | 1. L'utente accede alla sezione corsi.<br>2. Il sistema mostra l'elenco dei corsi attivi con dettagli (orario, posti disponibili, prezzo). |
| **Flusso secondario** |                                                                                                                                            |
##### UC06

| **Caso d'uso**        | Iscriversi a una lezione di un corso                                                                                                                                                                                                                                                                             |
| --------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC06                                                                                                                                                                                                                                                                                                             |
| **Descrizione**       | L'utente si iscrive ad una specifica lezione di un corso.                                                                                                                                                                                                                                                        |
| **Attore principale** | Utente                                                                                                                                                                                                                                                                                                           |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                                                                                          |
| **Precondizioni**     | 1. L'utente è autenticato.<br>2. Esistono corsi attivi con posti disponibili.                                                                                                                                                                                                                                    |
| **Postcondizioni**    | 1. L'utente risulta iscritto alla lezione.<br>2. Il numero di posti disponibili viene decrementato.                                                                                                                                                                                                              |
| **Flusso principale** | 1. L'utente visualizza i corsi disponibili (**UC05**)<br>2. L'utente seleziona un corso e visualizza le lezioni<br>3. L'utente seleziona una lezione con posti disponibili<br>4. L'utente effettua il pagamento<br>5. Il sistema conferma l'iscrizione e aggiorna i posti disponibili                            |
| **Flusso secondario** | **Flusso A - Posti esauriti**<br>1. Il sistema rileva che non ci sono più posti disponibili<br>2. Viene mostrato un messaggio di errore<br>3. L'elenco viene aggiornato<br><br>**Flusso secondario B - Errore pagamento**<br>1. Il pagamento non va a buon fine.<br>2. Il sistema mostra un messaggio di errore. |
##### UC07

| **Caso d'uso**        | Cancellare prenotazione                                                                                                                                                                                                                                                              |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **ID**                | UC07                                                                                                                                                                                                                                                                                 |
| **Descrizione**       | L'utente annulla una prenotazione esistente.                                                                                                                                                                                                                                         |
| **Attore principale** | Utente                                                                                                                                                                                                                                                                               |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                                                              |
| **Precondizioni**     | 1. Utente autenticato.<br>2. Esiste una prenotazione futura.<br>3. Mancano più di 24 ore all'evento.                                                                                                                                                                                 |
| **Postcondizioni**    | 1. La prenotazione è cancellata.<br>2. Database aggironato<br>3. Lo slot o il posto torna disponibile.<br>4. Eventuale rimborso.                                                                                                                                                     |
| **Flusso principale** | 1. L'utente visualizza lo storico (**UC10**).<br>2. L'utente seleziona la prenotazione da cancellare.<br>3. Il sistema verifica le condizioni di cancellazione.<br>4. La prenotazione viene aggiornata nel database in stato "cancellata"<br>5. Il sistema conferma la cancellazione |
| **Flusso secondario** | **Flusso secondario A - Cancellazione non consentita**<br>1. Il sistema rileva che la prenotazione non può essere cancellata<br>2. Viene mostrato un messaggio con la motivazione<br>3. La prenotazione rimane attiva                                                                |
##### UC08

| **Caso d'uso**        | Effettuare pagamento singolo                                                                                                                                                                                                   |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **ID**                | UC08                                                                                                                                                                                                                           |
| **Descrizione**       | Il sistema gestisce il pagamento per una prenotazione o lezione di un corso.                                                                                                                                                   |
| **Attore principale** | Utente                                                                                                                                                                                                                         |
| **Attore secondario** | Sistema, Sistema di Pagamento esterno (simulato)                                                                                                                                                                               |
| **Precondizioni**     | 1. L'utente è autenticato.<br>2. L'utente ha selezionato un servizio da pagare.                                                                                                                                                |
| **Postcondizioni**    | 1. Il pagamento è registrato nel sistema.<br>2. Il servizio viene confermato.                                                                                                                                                  |
| **Flusso principale** | 1. Il sistema mostra il riepilogo e l'importo.<br>2. L'utente seleziona il metodo di pagamento.<br>2. L'utente inserisce i dati di pagamento.<br>3. Il sistema processa la transazione.<br>4. Il sistema conferma il pagamento |
| **Flusso secondario** | **Flusso secondario A - Pagamento fallito**<br>1. Il sistema notifica l'errore.<br>2. L'utente può riprovare o annullare l'operazione                                                                                          |
##### UC09

| **Caso d'uso**        | Acquistare abbonamento                                                                                                                                                                                                                                             |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **ID**                | UC09                                                                                                                                                                                                                                                               |
| **Descrizione**       | Il cliente acquista un pacchetto di ingressi (mensile, trimestrale, annuale).                                                                                                                                                                                      |
| **Attore principale** | Utente                                                                                                                                                                                                                                                             |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                                            |
| **Precondizioni**     | 1. Utente autenticato.<br>2. Esistono abbonamenti disponibili per l'acquisto.                                                                                                                                                                                      |
| **Postcondizioni**    | 1. Abbonamento attivo associato al profilo utente.<br>2. L'utente può utilizzare l'abbonamento                                                                                                                                                                     |
| **Flusso principale** | 1. L'utente accede alla sezione abbonamenti.<br>2. Il sistema mostra gli abbonamenti disponibili con dettagli.<br>2. L'utente selezione un abbonamento.<br>3. L'utente effettua il pagamento (**UC08**).<br>4. Il sistema attiva l'abbonamento.                    |
| **Flusso secondario** | **Flusso secondario A - Abbonamento già attivo**<br>1. Il sistema rileva che l'utente ha già un abbonamento attivo<br>2. Viene mostrato un messaggio informativo<br>3. L'acquisto viene bloccato<br><br>**Flusso secondario B - Errore pagamento** (vedi **UC08**) |
##### UC10

| **Caso d'uso**        | Visualizzare storico prenotazioni e pagamenti                                                                                                       |
| --------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC10                                                                                                                                                |
| **Descrizione**       | L'utente visualizza lo storico delle proprie prenotazioni, iscrizioni e pagamenti effettuati                                                        |
| **Attore principale** | Utente                                                                                                                                              |
| **Attore secondario** | Sistema                                                                                                                                             |
| **Precondizioni**     | 1. Utente autenticato.                                                                                                                              |
| **Postcondizioni**    | 1. Viene visualizzato lo storico completo                                                                                                           |
| **Flusso principale** | 1. L'utente accede alla sezione storico.<br>2. Il sistema recupera i dati dal database.<br>3. Il sistema mostra l'elenco ordinato cronologicamente. |
| **Flusso secondario** | **Flusso secondario A - Visualizzazione dettaglio**<br>1. L'utente seleziona una voce dello storico<br>2. Il sistema mostra i dettagli completi     |
##### UC11

| **Caso d'uso**        | Gestire campi sportivi (Admin)                                                                                                                                                                                                      |
| --------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC11                                                                                                                                                                                                                                |
| **Descrizione**       | L'amministratore gestisce i campi sportivi (creazione, modifica, cancellazione)                                                                                                                                                     |
| **Attore principale** | Admin                                                                                                                                                                                                                               |
| **Attore secondario** | Sistema                                                                                                                                                                                                                             |
| **Precondizioni**     | 1. Utente autenticato come Admin.                                                                                                                                                                                                   |
| **Postcondizioni**    | 1. I campi sportivi sono aggiornati nel sistema                                                                                                                                                                                     |
| **Flusso principale** | 1. L'admin accede alla sezione gestione campi.<br>2. Il sistema mostra l'elenco dei campi esistenti.<br>3. L'admin può creare un nuovo campo, modificare o eliminare campi esistenti.<br>4. Il sistema valida e salva le modifiche. |
| **Flusso secondario** | **Flusso secondario A - Eliminazione con prenotazioni attive**<br>1. L'admin tenta di eliminare un campo con prenotazioni attive.<br>2. Il sistema mostra un avviso.<br>3. 3. L'admin può confermare o annullare.                   |
##### UC12

| **Caso d'uso**        | Gestire corsi e lezioni (Admin)                                                                                                                                                                                                                                    |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **ID**                | UC12                                                                                                                                                                                                                                                               |
| **Descrizione**       | L'amministratore gestisce i corsi e le relative lezioni (creazione, modifica, cancellazione)                                                                                                                                                                       |
| **Attore principale** | Admin                                                                                                                                                                                                                                                              |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                                            |
| **Precondizioni**     | 1. Utente autenticato come Admin.                                                                                                                                                                                                                                  |
| **Postcondizioni**    | 1. I corsi sono aggiornati e visibili agli utenti.                                                                                                                                                                                                                 |
| **Flusso principale** | 1. L'admin accede alla sezione gestione corsi.<br>2. Il sistema mostra l'elenco dei corsi.<br>3. L'admin può creare, modificare o eliminare corsi e lezioni<br>4. L'admin definisce orari, posti disponibili e prezzi<br>5. Il sistema valida e salva le modifiche |
| **Flusso secondario** | **Flusso secondario A - Modifica corso con iscrizioni**<br>1. L'admin modifica un corso con iscrizioni attive.<br>2. L'admin conferma o annulla.<br>3. Se confermato, gli utenti iscritti vengono notificati.                                                      |
##### UC13

| **Caso d'uso**        | Gestire tariffe e abbonamenti                                                                                                                                                                                                          |
| --------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                | UC13                                                                                                                                                                                                                                   |
| **Descrizione**       | L'amministratore imposta i prezzi per campi, corsi e abbonamenti.                                                                                                                                                                      |
| **Attore principale** | Admin                                                                                                                                                                                                                                  |
| **Attore secondario** | Sistema                                                                                                                                                                                                                                |
| **Precondizioni**     | 1. Utente autenticato come Admin.                                                                                                                                                                                                      |
| **Postcondizioni**    | 1. Le tariffe e gli abbonamenti sono aggiornati nel sistema<br>2. Le nuove tariffe si applicano alle prenotazioni successive                                                                                                           |
| **Flusso principale** | 1. L'admin accede alla sezione gestione tariffe.<br>2. Il sistema mostra le tariffe e gli abbonamenti attuali.<br>3. L'admin può creare, modificare o disattivare tariffe e abbonamenti.<br>4. Il sistema valida e salva le modifiche. |
| **Flusso secondario** |                                                                                                                                                                                                                                        |
