import Presenter from "../interfaces/Presenter.js"
import { eventBus } from "../utility/DefaultObserver.js";
import DatePicker from "./DatePicker.js";
import DatePickerPresenter from "./DatePickerPresenter.js";
import SlotPicker from "./SlotPicker.js";
import SlotPickerPresenter from "./SlotPickerPresenter.js";
import Events from "../utility/Events.js";

export default class CalendarPresenter extends Presenter {

    constructor(view){
        super(view)
    }

    #initComponents(){
        const datePicker = new DatePicker();
        const slotPicker = new SlotPicker();

        this._view.display({date : datePicker, slot : slotPicker});

        const slotPresenter = new SlotPickerPresenter(slotPicker);
        const datePickerPresenter = new DatePickerPresenter(datePicker);
        
        slotPresenter.init();
        datePickerPresenter.init();
    }

    _handleViewEvents(){
        eventBus.subscribe(Events.CALENDAR_LOAD_EVENT, () => {
            this.#initComponents()
        })
    }
}
