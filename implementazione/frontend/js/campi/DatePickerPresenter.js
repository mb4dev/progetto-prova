import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import Presenter from "../interfaces/Presenter.js"

export default class DatePickerPresenter extends Presenter {
	
	#week
	
	constructor(view){
		super(view);
		
		this.#week = this.#getWeekDays();
		this._view.display({ week: this.#week });
	}
	
	update() {  }
	
	_handleViewEvents() {

		this.#handleIncrement();
		this.#handleDecrement();

	}
	
	#handleIncrement(){
		eventBus.subscribe(Events.DATE_INCREMENT_EVENT, () => {
			const next = new Date(this.#week.at(0).date)
			next.setDate(next.getDate() + 7)
			
			this.#week = this.#getWeekDays(next)
			this._view.display({ week: this.#week })
		})
	}
	
	#handleDecrement(){
		eventBus.subscribe(Events.DATE_DECREMENT_EVENT, () => {
			
			const prev = new Date(this.#week.at(0).date)
			prev.setDate(prev.getDate() - 7)
			prev.setHours(0, 0, 0, 0)
			
			const today = new Date()
			today.setHours(0, 0, 0, 0)
			
			if (prev < today) {
				console.warn("Non puoi selezionare date passate")
				return
			}
			
			this.#week = this.#getWeekDays(prev)
			this._view.display({ week: this.#week })
		})
	}
	
	#getWeekDays(fromDate = new Date(), daysToShow = 7){
		const options = {
			weekday: "long"			
		}
		const days = [];
		
		for(let i = 0; i < daysToShow; i++){
			const date = new Date(fromDate) 
			date.setDate(date.getDate() + i)
			const day = date.toLocaleDateString("it-IT", options)
			const month = String(date.getMonth() + 1).padStart(2, '0')
			const dayNumber = String(date.getDate()).padStart(2, '0')
			
			const obj = {
				giorno: day, 
				numero: dayNumber, 
				mese: month, 
				date: date
			}
			days.push(obj)
		}
		
		return days;
	}
}