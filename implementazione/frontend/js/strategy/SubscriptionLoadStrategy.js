import LoadStrategy from "../interfaces/LoadStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class SubscriptionLoadStrategy extends LoadStrategy {
    load() {
        return apiService.getSubscriptions();
    }
}

