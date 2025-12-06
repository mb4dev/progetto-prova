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

- **RF08. Visualizzazione corsi** - Il cliente può iscriversi a una singola lezione di un corso, solo se sono disponibili posti.

- **RF09. Iscrizione a un corso** - L'utente, se nel corso c'è ancora spazio disponibile, può iscriversi per la singola lezione

- **RF10. Pagamento singolo** - Il sistema deve consentire il pagamento di una prenotazione o di una lezione senza utilizzo di abbonamento.

- **RF11. Gestione abbonamenti** - Il sistema deve consentire l’acquisto di abbonamenti mensili, trimestrali o annuali che permettono un numero definito di ingressi ai corsi

### Requisiti non funzionali

- **RNF01** - Password memorizzare in forma cifrata
- **RNF02** - Accesso alle funzionalità basato su ruoli
- **RNF03** - Interfaccia web responsive
- **RNF04** - Nessuna doppia prenotazione della stessa risorsa
