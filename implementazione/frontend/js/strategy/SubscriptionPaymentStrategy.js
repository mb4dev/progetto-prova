import PaymentStrategy from "../interfaces/PaymentStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

/**
 * Strategia di pagamento per gli abbonamenti.
 * Si aspetta tipicamente un subscriptionId (e opzionalmente altri dati).
 */
export default class SubscriptionPaymentStrategy extends PaymentStrategy {
    pay(data) {
        // data può contenere, ad esempio, { subscriptionId }
        return apiService.processSubscriptionPayment
            ? apiService.processSubscriptionPayment(data)
            : Promise.reject(new Error("processSubscriptionPayment non disponibile"));
    }
}

