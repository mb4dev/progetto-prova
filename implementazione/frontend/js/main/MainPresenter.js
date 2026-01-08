import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";
import ViewFactory from "../utility/ViewFactory.js";

export default class MainPresenter extends Presenter {

	constructor(view){
		super(view)

	}

	/*
	init(){
		this._handleViewEvents();
		eventBus.notify(Events.MAIN_NAVIGATE, { main: Routes.MAIN_CAMPI });
	}
	*/

	_handleViewEvents(){
		eventBus.subscribe(Events.MAIN_NAVIGATE, (data) => {
			const route = data.main;
			const components = ViewFactory.createView(route);
			if(!components) {
				throw new Error(`Errore creazione view di tipo ${data.main}`);
			}
			this._view.display({ view: components.view, route: data.main });
		});
	}
}
