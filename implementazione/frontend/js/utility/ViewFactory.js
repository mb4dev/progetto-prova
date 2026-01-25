import Routes from "./Routes.js";
import ProfileView from "../profile/ProfileView.js";
import ProfilePresenter from "../profile/ProfilePresenter.js";
import SportSelectionView from "../sport-selection/SportSelectionView.js";
import SportSelectionPresenter from "../sport-selection/SportSelectionPresenter.js";
import CalendarView from "../prenotazione/CalendarView.js";
import ItemType from "./ItemType.js"
import CalendarPresenterV2 from "../prenotazione/CalendarPresenterV2.js";
import CourseSlotLoadStrategy from "../strategy/CourseSlotLoadStrategy.js";
import FieldsLoadStrategy from "../strategy/FieldsLoadStrategy.js";
import CoursesLoadStrategy from "../strategy/CoursesLoadStrategy.js";
import SubscriptionLoadStrategy from "../strategy/SubscriptionLoadStrategy.js";
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
import ConfirmPaymentCommand from "../commands/ConfirmPaymentCommand.js";
import NormalPaymentStrategy from "../strategy/NormalPaymentStrategy.js";
import SubscriptionPaymentStrategy from "../strategy/SubscriptionPaymentStrategy.js";
import HistoryView from "../history/HistoryView.js";
import HistoryPresenter from "../history/HistoryPresenter.js";
import HistoryLoadStrategy from "../strategy/HistoryLoadStrategy.js";
import SubscriptionView from "../subscription/SubscriptionView.js";
import SubscriptionPresenter from "../subscription/SubscriptionPresenter.js";
import AdminView from "../admin/AdminView.js";
import AdminPresenter from "../admin/AdminPresenter.js";
import ProfileUpdateCommand from "../commands/ProfileUpdateCommand.js";


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
			case Routes.MAIN_STORICO:
				return this.#createHistoryView()
			case Routes.MAIN_ADMIN:
				return this.#createAdminView()
			case Routes.MAIN_ABBONAMENTO:
				return this.#createSubscriptionView()
			case Routes.MAIN_CALENDARIO:
				return this.#createCalendarView()
			case Routes.MAIN_PAYMENT:
				return this.#createPayment()
			/*
				case Routes.MAIN_PAYMENT_SINGLE:
				return this.#createPaymentSingle()
			case Routes.MAIN_PAYMENT_SUBSCRIPTION:
				return this.#createPaymentSubscription()
			*/
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
		const presenter = new ProfilePresenter(view, {
			onUpdateCommand: new ProfileUpdateCommand()
		});
		return {view : view, presenter: presenter};
	}

	static #createCalendarView(){
		const view  = new CalendarView();
		const presenter = new CalendarPresenterV2(view, {
			loadStrategy: new CourseSlotLoadStrategy(),
			onConfirmCommand: new NavigateCommand(Routes.MAIN_PAYMENT_SINGLE),
		})
		return {view : view, presenter: presenter};
	}

	static #createHistoryView() {
		const view = new HistoryView();
		const presenter = new HistoryPresenter(view, {
			loadStrategy: new HistoryLoadStrategy(),
			onDeleteCommand:  { execute: (id) => console.log("richiesta cancellazione " + id) }
		});
		return { view: view, presenter: presenter };
	}

	static #createSubscriptionView() {
		const view = new SubscriptionView();
		const presenter = new SubscriptionPresenter(view, {
			loadStrategy: new SubscriptionLoadStrategy(),
			onSelectedCommand: new NavigateCommand(Routes.MAIN_PAYMENT_SUBSCRIPTION),
		});
		return { view: view, presenter: presenter };
	}

	static #createAdminView() {
		const view = new AdminView();
		const presenter = new AdminPresenter(view, {});
		return { view: view, presenter: presenter };
	}

	static #createPayment(){
		const view = new PaymentView();
		const presenter = new PaymentPresenter(view, {
			paymentStrategy: new NormalPaymentStrategy(),
			onConfirmPaymentCommand : new ConfirmPaymentCommand()
		})

		return {view : view, presenter: presenter};
	}

	/*
	static #createPaymentSingle(){
		const view = new PaymentView();
		const presenter = new PaymentPresenter(view, {
			paymentStrategy: new NormalPaymentStrategy(),
			onConfirmPaymentCommand : new ConfirmPaymentCommand()
		})

		return {view : view, presenter: presenter};
	}


	static #createPaymentSubscription() {
		const view = new PaymentView();
		const presenter = new PaymentPresenter(view, {
			paymentStrategy: new SubscriptionPaymentStrategy(),
			onConfirmPaymentCommand: new ConfirmPaymentCommand()
		});

		return { view: view, presenter: presenter };
	}
		*/
}
