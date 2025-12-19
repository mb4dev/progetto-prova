import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class MainPresenter extends Presenter {

	constructor(view, service){
		super(view, service)
	}

	init(){
		this._handleViewEvents();
	}

	_handleViewEvents(){
		eventBus.subscribe(Events.MAIN_SELECT_EVENT, (data) => {
			console.log("selezionato menu: " + data.main)
		});
	}
}