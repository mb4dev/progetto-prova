
import PaymentStrategy from "../interfaces/PaymentStrategy.js";
import { apiService } from "../utility/MockAPIService.js";


export default class NormalPaymentStrategy extends PaymentStrategy {
    pay(data) {
        console.log("pagamento singolo", data)
        /*
        return apiService.processSinglePayment
            ? apiService.processSinglePayment(data)
            : Promise.reject(new Error("processSinglePayment non disponibile"));
        */
    }
}


