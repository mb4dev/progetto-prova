import Presenter from "../interfaces/Presenter.js"
import { eventBus } from "../utility/DefaultObserver.js";
import DatePicker from "./DatePicker.js";
import DatePickerPresenter from "./DatePickerPresenter.js";
import SlotPicker from "./SlotPicker.js";
import SlotPickerPresenter from "./SlotPickerPresenter.js";
import Events from "../utility/Events.js";
import ReservationResumePresenter from "./ReservationResumePresenter.js";
import ReservationResumeView from "./ReservationResumeView.js";


export default class CalendarPresenter extends Presenter {

    #week

    constructor(view){
        super(view)
    }

    #initComponents(){
        const datePicker = new DatePicker();
        const slotPicker = new SlotPicker();
        const resume = new ReservationResumeView();

        this._view.display({date : datePicker, slot : slotPicker, resume: resume});

        const slotPresenter = new SlotPickerPresenter(slotPicker);
        const datePickerPresenter = new DatePickerPresenter(datePicker);
        const resumePresenter = new ReservationResumePresenter(resume);
        
        slotPresenter.init();
        datePickerPresenter.init();
        resumePresenter.init();
    }

    _handleViewEvents(){
        eventBus.subscribe(Events.CALENDAR_LOAD_EVENT, () => {
            this.#initComponents()
        })

        eventBus.subscribe(Events.DATE_INCREMENT_EVENT, () => {
			const next = new Date(this.#week.at(0).fullDate)
			next.setDate(next.getDate() + 7)
			
			this.#week = this.#getWeekDays(next)
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
				fullDate: date.toISOString().split('T')[0],
			}

			days.push(obj)
		}
		
		return days;
	}
}
