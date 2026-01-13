import LoadStrategy from "../interfaces/LoadStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class FieldsLoadStrategy extends LoadStrategy {
    load(){
        return apiService.getSports()
    }
}