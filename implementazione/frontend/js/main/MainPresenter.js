import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Routes from "../utility/Routes.js";
import ProfileView from "../profile/ProfileView.js";
import ProfilePresenter from "../profile/ProfilePresenter.js";
import CampiView from "../campi/CampiView.js";
import CampiPresenter from "../campi/CampiPresenter.js";
import CorsiView from "../corsi/CorsiView.js";
import CorsiPresenter from "../corsi/CorsiPresenter.js";
import AbbonamentoView from "../abbonamento/AbbonamentoView.js";
import AbbonamentoPresenter from "../abbonamento/AbbonamentoPresenter.js";
import StoricoView from "../storico/StoricoView.js";
import StoricoPresenter from "../storico/StoricoPresenter.js";

export default class MainPresenter extends Presenter {
	#registry

	constructor(view, service){
		super(view, service)
		this.#registry = {
			[Routes.MAIN_PROFILE]: { view: ProfileView, presenter: ProfilePresenter },
			[Routes.MAIN_CAMPI]: { view: CampiView, presenter: CampiPresenter },
			[Routes.MAIN_CORSI]: { view: CorsiView, presenter: CorsiPresenter },
			[Routes.MAIN_ABBONAMENTO]: { view: AbbonamentoView, presenter: AbbonamentoPresenter },
			[Routes.MAIN_STORICO]: { view: StoricoView, presenter: StoricoPresenter },
		}
	}

	init(){
		this._handleViewEvents();
		eventBus.notify(Events.MAIN_SELECT_EVENT, { main: Routes.MAIN_PROFILE });
	}

	_handleViewEvents(){
		eventBus.subscribe(Events.MAIN_SELECT_EVENT, (data) => {
			const route = this.#registry[data.main];
			
			if (route) {
				const view = new route.view();
				const presenter = new route.presenter(view, this._service);
				presenter.init();
				this._view.display({ view: view, route: data.main });
			} else {
				console.error(`No route found for: ${data.main}`);
			}
		});
	}
}