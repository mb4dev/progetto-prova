import LoadStrategy from "../interfaces/LoadStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class CoursesLoadStrategy extends LoadStrategy {
    load(){
        return apiService.getCourses()
    }
}