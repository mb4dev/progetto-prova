import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Routes from "../utility/Routes.js";

export default class ProfilePresenter extends Presenter {

	constructor(view, config = {}){

		if (!config.onUpdateCommand) throw new Error("configurazione minima mancante")
		super(view, config)
	}

	init(){
		const user = JSON.parse(localStorage.getItem("user"));

		this._view.display({profile: user})
		this._handleViewEvents();
	}

	_handleViewEvents(){
		eventBus.subscribe(Events.PROFILE_UPDATE_EVENT, async (data) => {
			console.log("aggiornamento profilo")
			const newUser = await this._config.onUpdateCommand.execute(data);
			
			this._view.display({profile: newUser, mode: "view"});
			eventBus.notify(Events.PROFILE_UPDATED, newUser);
		});
	}
}