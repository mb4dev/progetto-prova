import Presenter from "../interfaces/Presenter.js"
import { eventBus } from "../utility/DefaultObserver.js";
import DatePicker from "./DatePicker.js";
import DatePickerPresenter from "./DatePickerPresenter.js";
import SlotPicker from "./SlotPicker.js";
import SlotPickerPresenter from "./SlotPickerPresenter.js";
import Events from "../utility/Events.js";
import ReservationResumePresenter from "./ReservationResumePresenter.js";
import ReservationResumeView from "./ReservationResumeView.js";


export default class CalendarPresenterV2 extends Presenter {

    constructor(view){
        super(view)
    }

    #initComponents(){
        const datePicker = new DatePicker();
        const slotPicker = new SlotPicker();
        const resume = new ReservationResumeView();

        this._view.display({date : datePicker, slot : slotPicker, resume: resume});
    }

    _handleViewEvents(){
        eventBus.subscribe(Events.CALENDAR_LOAD_EVENT, () => {
            this.#initComponents()
        })
    }
}
