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
import CalendarView from "../campi/CalendarView.js";
import CalendarPresenter from "../campi/CalendarPresenter.js";

export default class MainPresenter extends Presenter {
	#registry

	constructor(view){
		super(view)
		this.#registry = {
			[Routes.MAIN_PROFILE]: { view: ProfileView, presenter: ProfilePresenter },
			[Routes.MAIN_CAMPI]: { view: CampiView, presenter: CampiPresenter },
			[Routes.MAIN_CORSI]: { view: CorsiView, presenter: CorsiPresenter },
			[Routes.MAIN_ABBONAMENTO]: { view: AbbonamentoView, presenter: AbbonamentoPresenter },
			[Routes.MAIN_STORICO]: { view: StoricoView, presenter: StoricoPresenter },

			[Routes.MAIN_CALENDARIO] : {view: CalendarView, presenter: CalendarPresenter}
		}
	}

	init(){
		this._handleViewEvents();
		eventBus.notify(Events.MAIN_NAVIGATE, { main: Routes.MAIN_CALENDARIO });
	}

	_handleViewEvents(){
		eventBus.subscribe(Events.MAIN_NAVIGATE, (data) => {
			const route = this.#registry[data.main];
			
			if (route) {
				const view = new route.view();
				const presenter = new route.presenter(view);
				presenter.init();
				this._view.display({ view: view, route: data.main });
			} else {
				console.error(`No route found for: ${data.main}`);
			}
		});
	}
}
