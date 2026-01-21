import HistoryStatus from "../history/HistoryStatus.js";
import LoadStrategy from "../interfaces/LoadStrategy.js";
import { apiService } from "../utility/MockAPIService.js";


export default class HistoryLoadStrategy extends LoadStrategy {
	async load(){
		return apiService.getHistory()
			.then(response => {
				if (response.success && response.data) {
					const processedData = this.processHistoryData(response.data);
					return { ...response, data: processedData };
				}
				return response;
			});
	}

	processHistoryData(items) {
		const today = new Date();
		today.setHours(0, 0, 0, 0);

		return items.map(item => {
			const itemDate = new Date(item.date);
			itemDate.setHours(0, 0, 0, 0);
			
			const daysDiff = Math.floor((itemDate - today) / (1000 * 60 * 60 * 24));
			
			let status= "";

			if (daysDiff >= 1) {
				status = HistoryStatus.CANCELLABLE;
			}
			else if(daysDiff < 1 && daysDiff > 0) {
				status = HistoryStatus.IMMINENT
			}
			else {
				status = HistoryStatus.EXPIRED
			}


			return {
				...item,
				status: status
			};
		});
	}
}

