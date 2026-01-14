import PaymentStrategy from "../interfaces/PaymentStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

/**
 * Strategia di pagamento per i pagamenti singoli (carrello normale).
 */
export default class NormalPaymentStrategy extends PaymentStrategy {
    pay(data) {
        // data dovrebbe contenere almeno { items, total }
        return apiService.processSinglePayment
            ? apiService.processSinglePayment(data)
            : Promise.reject(new Error("processSinglePayment non disponibile"));
    }
}

