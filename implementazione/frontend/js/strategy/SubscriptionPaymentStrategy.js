import PaymentStrategy from "../interfaces/PaymentStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class SubscriptionPaymentStrategy extends PaymentStrategy {
    pay(data) {
        /*
        return apiService.processSubscriptionPayment
            ? apiService.processSubscriptionPayment(data)
            : Promise.reject(new Error("processSubscriptionPayment non disponibile"));
            */
    }
}

