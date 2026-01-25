import LoadStrategy from "../interfaces/LoadStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class CourseSlotLoadStrategy extends LoadStrategy {
	load(data){
		return apiService.getOccupiedSlotsForWeek(data.date, data.resourceId, data.resourceType)
	}
}
