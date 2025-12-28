import { eventBus } from "../DefaultObserver.js";
import Events from "../Events.js";
import Presenter from "../presenter.js"

export default class SlotPickerPresenter extends Presenter {
	
	#selected;

	constructor(view){
		super(view);

		this.#selected = new Set();
	}


	update() {  }

	_handleViewEvents() {
		eventBus.subscribe(Events.SLOT_SELECTED_EVENT, d => { console.log(d.time)})

	}

}