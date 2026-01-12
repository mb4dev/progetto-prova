import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";
import ViewFactory from "../utility/ViewFactory.js";
import Routes from "../utility/Routes.js";

export default class MainPresenter extends Presenter {

	constructor(view){
		super(view)
	}

	init(){
		this._handleViewEvents()
	}
	

	_handleViewEvents(){
		eventBus.subscribe(Events.MAIN_NAVIGATE, (data) => {
			const route = data.main;
			const components = ViewFactory.create(route);
			if(!components) {
				throw new Error(`Errore creazione view di tipo ${data.main}`);
			}

		
			this._view.display({ view: components.view, route: data.main });
		});
	}
}
