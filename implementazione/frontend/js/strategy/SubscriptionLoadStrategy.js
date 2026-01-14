import LoadStrategy from "../interfaces/LoadStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

/**
 * Strategia di caricamento per la lista degli abbonamenti.
 */
export default class SubscriptionLoadStrategy extends LoadStrategy {
    load() {
        return apiService.getSubscriptions();
    }
}

