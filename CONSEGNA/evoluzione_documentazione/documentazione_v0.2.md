# Gestione CUS PARMA

Sistema semplificato per la prenotazione di campi (es. tennis, calcetto)
o corsi (es. palestra) presso il CUS. Pagamenti (simulati), gestione abbonamenti vs pagamenti
singoli, orari e disponibilità risorse (campi/istruttori).

### Requisiti Funzionali

- **RF01. Registrazione/Autenticazione** - L'utente deve potersi registrare con email e password

- **RF02. Gestione profilo utente** - L'utente può visualizzare dati personali.

- **RF03. Visualizzazione storico prenotazioni e ordini** - L’utente può visualizzare lo storico delle prenotazioni effettuate, dei pagamenti e lo stato degli eventuali abbonamenti.

- **RF04. Ruoli** - Il sistema deve distinguere tra: 
	- **Cliente**: prenotazione campi, iscrizione ai corsi, pagamenti
	- **Admin/Istruttore:** gestione campi sportivi, corsi, utenti e tariffe

- **RF05. Visualizzazione disponibilità campo** - Il sistema deve mostrare gli slot temporali disponibili per la prenotazione del campo sportivo

- **RF06. Prenotazione campo sportivo** - Il cliente può prenotare un campo sportivo selezionando slot temporali con durata minima di 30 minuti.

- **RF07. Cancellazione prenotazione** - Il cliente può cancellare una prenotazione fino a 24 ore prima dell’orario previsto.

- **RF08. Visualizzazione corsi** - Il sistema mostra l’elenco dei corsi e delle singole lezioni disponibili

- **RF09. Iscrizione a un corso** - L'utente, se nel corso c'è ancora spazio disponibile, può iscriversi per la singola lezione

- **RF10. Pagamento singolo** - Il sistema deve consentire il pagamento di una prenotazione o di una lezione senza utilizzo di abbonamento.

- **RF11. Gestione abbonamenti** - Il sistema deve consentire l’acquisto di abbonamenti mensili, trimestrali o annuali che permettono un numero definito di ingressi ai corsi

- **RF12. Memorizzazione pagamenti** - Il sistema deve mantenere in memoria eventuali pagamenti/abbonamenti 

### Requisiti non funzionali

- **RNF01** - Password memorizzare in forma cifrata
- **RNF02** - Accesso alle funzionalità basato su ruoli
- **RNF03** - Interfaccia web responsive
- **RNF04** - Nessuna doppia prenotazione della stessa risorsa

### Dipendenze tra requisiti

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
