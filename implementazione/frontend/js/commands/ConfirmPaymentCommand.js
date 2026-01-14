import Command from "../interfaces/Command.js";

/**
 * Comando eseguito dopo che il pagamento è stato completato con successo.
 * Al momento si limita a loggare, ma può essere esteso per navigare
 * verso una schermata di conferma o aggiornare lo storico.
 */
export default class ConfirmPaymentCommand extends Command {
    constructor() {
        super();
    }

    execute(data) {
        console.log("confermato pagamento", data?.response);
    }
}
