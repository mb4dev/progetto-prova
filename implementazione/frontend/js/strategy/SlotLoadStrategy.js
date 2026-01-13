import LoadStrategy from "../interfaces/LoadStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class SlotLoadStrategy extends LoadStrategy {
	load(data){
		console.log(data.date)
		return apiService.getOccupiedSlotsForWeek(data.date)
	}
}