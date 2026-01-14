export default class PaymentStrategy {
    /**
     * Esegue il pagamento in base alla configurazione
     * (es. singolo pagamento vs abbonamento).
     * Deve restituire una Promise con una Response compatibile con MockAPIService.
     *
     * @param {Object} data - Dati necessari al pagamento (es. items, total, subscriptionId, ecc.)
     * @returns {Promise}
     */
    pay(data) {
        throw new Error("Metodo pay non implementato");
    }
}

