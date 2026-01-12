import Routes from "./Routes.js";
import ProfileView from "../profile/ProfileView.js";
import ProfilePresenter from "../profile/ProfilePresenter.js";
import SportSelectionView from "../sport-selection/SportSelectionView.js";
import SportSelectionPresenter from "../sport-selection/SportSelectionPresenter.js";
import CalendarView from "../prenotazione/CalendarView.js";
import ItemType from "./ItemType.js"
import CalendarPresenterV2 from "../prenotazione/CalendarPresenterV2.js";
import SlotLoadStrategy from "../strategy/SlotLoadStrategy.js";
import FieldsLoadStrategy from "../strategy/FieldsLoadStrategy.js";
import CoursesLoadStrategy from "../strategy/CoursesLoadStrategy.js";
import NavigateCommand from "../commands/NavigateCommand.js";
import LoginView from "../auth/LoginView.js";
import RegisterView from "../auth/RegisterView.js";
import AuthPresenter from "../auth/AuthPresenter.js";
import LoginStrategy from "../strategy/LoginStrategy.js";
import RegisterStrategy from "../strategy/RegisterStrategy.js";
import MainView from "../main/MainView.js";
import MainPresenter from "../main/MainPresenter.js";
import PaymentView from "../payment/PaymentView.js";
import PaymentPresenter from "../payment/PaymentPresenter.js";


export default class ViewFactory {	
	static create(route){

		switch(route){
			case Routes.LOGIN:
				return this.#createLoginView()
			case Routes.REGISTER:
				return this.#createRegisterView()
			case Routes.MAIN:
				return this.#createMainView()
			case Routes.MAIN_CAMPI:
				return this.#createPrenotazioneCampi()
			case Routes.MAIN_CORSI:
				return this.#createPrenotazioneCorsi()
			case Routes.MAIN_PROFILE:
				return this.#createProfileView()
			case Routes.MAIN_CALENDARIO:
				return this.#createCalendarView()
			case Routes.MAIN_PAYMENT:
				return this.#createPayment()
			default:
			console.error("Route non supportata dalla Factory:", route);
			return null;
		}
	}

	static #createLoginView(){
		const view = new LoginView();

		const config = {
			authStrategy: new LoginStrategy(),
			onSuccessCommand: new NavigateCommand(Routes.MAIN),
			onNavigateCommand: new NavigateCommand(Routes.REGISTER)
		}
		const presenter = new AuthPresenter(view, config);
		return {view : view, presenter: presenter};
	}

	static #createRegisterView(){
		const view = new RegisterView();
		const presenter = new AuthPresenter(view, {
			authStrategy: new RegisterStrategy(),
			onSuccessCommand: new NavigateCommand(Routes.MAIN),
			onNavigateCommand: new NavigateCommand(Routes.LOGIN)
		});
		return {view : view, presenter: presenter};
	}

	static #createMainView(){
		const view = new MainView();
		const presenter = new MainPresenter(view);
		return {view : view, presenter: presenter};
	}
	
	static #createPrenotazioneCampi(){
		const view = new SportSelectionView({
			title: "Prenotazione campi sportivi",
			subtitle: "Seleziona il campo sportivo che preferisci",
			itemType: ItemType.FIELD,
		});
		const presenter = new SportSelectionPresenter(view, {
			loadStrategy: new FieldsLoadStrategy(),
			onSelectedCommand:  new NavigateCommand(Routes.MAIN_CALENDARIO)
		});
		
		return {view : view, presenter: presenter};
	}
	
	static #createPrenotazioneCorsi(){
		const view = new SportSelectionView({
			title: "Prenotazione corso",
			subtitle: "Seleziona il corso che preferisci",
			itemType: ItemType.COURSE,
		});
		
		const config = {
			loadStrategy: new CoursesLoadStrategy(),
			onSelectedCommand: new NavigateCommand(Routes.MAIN_CALENDARIO)
		}
		const presenter = new SportSelectionPresenter(view, config);
		return {view : view, presenter: presenter};
		
	}

	static #createProfileView(){
		const view = new ProfileView();
		const presenter = new ProfilePresenter(view);
		return {view : view, presenter: presenter};
	}

	static #createCalendarView(){
		const view  = new CalendarView();
		const presenter = new CalendarPresenterV2(view, {
			loadStrategy: new SlotLoadStrategy(),
			onConfirmCommand: new NavigateCommand(Routes.MAIN_PAYMENT),
		})
		return {view : view, presenter: presenter};
	}

	static #createPayment(){
		const view = new PaymentView();
		const presenter = new PaymentPresenter(view, {})
		
		return {view : view, presenter: presenter};
	}
}
