import Routes from "./Routes.js";
import ProfileView from "../profile/ProfileView.js";
import ProfilePresenter from "../profile/ProfilePresenter.js";
import SportSelectionView from "../sport-selection/SportSelectionView.js";
import SportSelectionPresenter from "../sport-selection/SportSelectionPresenter.js";
import CalendarView from "../campi/CalendarView.js";
import ItemType from "./ItemType.js"
import FieldsLoadStrategy from "../strategy/FieldsLoadStrategy.js";
import CoursesLoadStrategy from "../strategy/CoursesLoadStrategy.js";
import NavigateToCalendarCommand from "../commands/NavigateToCalendarCommand.js";
import CalendarPresenterV2 from "../campi/CalendarPresenterV2.js";

export default class ViewFactory {
	static #cache = new Map();
	
	static createView(route){

		if (this.#cache.has(route)) {
			console.log(`ViewFactory - riutilizzo per ${route}`);
			return this.#cache.get(route);
		}

		console.log(`ViewFactory - creazione ${route}`);
		let components = null;

		switch(route){
			case Routes.MAIN_CAMPI:
				components = this.#createPrenotazioneCampi();
				break;
			case Routes.MAIN_CORSI:
				components = this.#createPrenotazioneCorsi();
				break;
			case Routes.MAIN_PROFILE:
				components = this.#createProfileView();
				break;
			case Routes.MAIN_CALENDARIO:
				components = this.#createCalendarView();
				break;
			default:
				console.error("Route non supportata dalla Factory:", route);
				return null;
		}

		if (components) {
			this.#cache.set(route, components);
		}

		return components;
	}
	
	static #createPrenotazioneCampi(){
		const view = new SportSelectionView({
			title: "Prenotazione campi sportivi",
			subtitle: "Seleziona il campo sportivo che preferisci",
			itemType: ItemType.FIELD,
		});
		const presenter = new SportSelectionPresenter(view, {
			loadStrategy: new FieldsLoadStrategy(),
			onSelectedCommand:  new NavigateToCalendarCommand()
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
			onSelectedCommand: new NavigateToCalendarCommand()
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
		const presenter = new CalendarPresenterV2(view)
		return {view : view, presenter: presenter};
	}

	/*
	// Metodo opzionale per forzare la ricreazione di una view
	static clearCache(route = null) {
		if (route) {
			console.log(`ViewFactory: pulizia cache per ${route}`);
			this.#instances.delete(route);
		} else {
			console.log("ViewFactory: pulizia completa cache");
			this.#instances.clear();
		}
	}

	// Metodo opzionale per vedere lo stato della cache
	static getCacheStatus() {
		return {
			size: this.#instances.size,
			routes: Array.from(this.#instances.keys())
		};
	}
	*/
}