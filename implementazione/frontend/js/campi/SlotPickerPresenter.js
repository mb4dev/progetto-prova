import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import Presenter from "../interfaces/Presenter.js"

export default class SlotPickerPresenter extends Presenter {
	
	#selected;

	constructor(view){
		super(view);

		this.#selected = new Set();
	}


	update() {  }

	_handleViewEvents() {
		eventBus.subscribe(Events.SLOT_SELECTED_EVENT, d => { 
			const time = d.time;
			if (!time) return 

			this.#selected.has(time) ? this.#selected.delete(time) : this.#selected.add(time)
			this._view.display({selected: this.#selected})
		})

	}

}