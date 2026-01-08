import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import Presenter from "../interfaces/Presenter.js"
import { apiService } from "../utility/MockAPIService.js";

export default class SlotPickerPresenter extends Presenter {
	
	#selected;
	#occupied;
	
	#selectedDate;

	constructor(view){
		super(view);

		this.#selected = new Set();
		this.#occupied = [];

	}

	update() { 
		this._view.display({
			selectedDate: this.#selectedDate,
			selected: this.#selected, 
			occupied: this.#occupied})
	}

	_handleViewEvents() {
		eventBus.subscribe(Events.SLOT_SELECTED_EVENT, d => { 
			const time = d.time;
			if (!time) return 

			this.#selected.has(time) ? this.#selected.delete(time) : this.#selected.add(time)
			this.update()
		})

		
		eventBus.subscribe(Events.DATE_SELECTED_EVENT, data => {
            if(!data.selectedDate) return;

			this.#selectedDate = data.selectedDate
            apiService.getOccupiedSlotsForWeek(data.selectedDate).then(result => {
                this.#occupied = result.data[data.selectedDate]; 
                this.update();		
            });
        });
	
	}

}