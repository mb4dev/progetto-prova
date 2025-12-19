import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Routes from "../utility/Routes.js";

export default class ProfilePresenter extends Presenter {

	constructor(view, service){
		super(view, service)
	}

	init(){
		this._service.getProfile().then(response => {
			if (response.success) {
				this._view.display({ profile: response.data });
			}
		});
		this._handleViewEvents();
	}

	_handleViewEvents(){
		eventBus.subscribe(Events.PROFILE_UPDATE_EVENT, (data) => {
			this._service.updateProfile(data).then(response => {
				if (response.success) {
					this._view.display({ profile: response.data, mode: "view" });
				}
			});
		});
	}
}