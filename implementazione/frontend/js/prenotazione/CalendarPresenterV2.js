import Presenter from "../interfaces/Presenter.js"
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import { apiService } from "../utility/MockAPIService.js";
import DatePicker from "./DatePicker.js";
import SlotPicker from "./SlotPicker.js";
import ReservationResumeView from "./ReservationResumeView.js";
import ReservationState from "./ReservationState.js";

export default class CalendarPresenterV2 extends Presenter {
    #views = {};
    #state

    constructor(view, config) {
        if(!config.loadStrategy || !config.onConfirmCommand || !config.onBackCommand) throw new Error("Configurazione minima mancante");
        super(view, config);

        this.#state = new ReservationState()
    }

    _handleViewEvents() {
        eventBus.subscribe(Events.CALENDAR_LOAD_EVENT, () => {
            this.#initComponents();
        });

        eventBus.subscribe(Events.DATE_SELECTED_EVENT, (data) => {
            this.#state.selectedDate = data.selectedDate;
            this.#state.selectedSlots.clear();

            this._config.loadStrategy.load({date: data.selectedDate}).then(result => {
                const allSlots = result.data || {};
                this.#state.occupiedSlots = allSlots[data.selectedDate] || []; 
                this.#updateSlotPicker();
                this.#updateResume();
            });
        });

        eventBus.subscribe(Events.DATE_INCREMENT_EVENT, () => this.#state.changeWeek(7,this.#updateDatePicker()));
        eventBus.subscribe(Events.DATE_DECREMENT_EVENT, () => this.#state.changeWeek(-7, this.#updateDatePicker()));

        eventBus.subscribe(Events.SLOT_SELECTED_EVENT, (data) => {
            const { time } = data;
            if (this.#state.selectedSlots.has(time)) {
                this.#state.selectedSlots.delete(time);
            } else {
                this.#state.selectedSlots.add(time);
            }
            this.#updateSlotPicker();
            this.#updateResume();
        });
    }

    #initComponents() {
        this.#views.date = new DatePicker();
        this.#views.slot = new SlotPicker();
        this.#views.resume = new ReservationResumeView();

        this._view.display({
            date: this.#views.date,
            slot: this.#views.slot,
            resume: this.#views.resume
        });

        this.#updateDatePicker();
    }

    #updateDatePicker() {
        this.#views.date.display({ week: this.#state.week });
    }
1
    #updateSlotPicker() {
        this.#views.slot.display({
            selectedDate: this.#state.selectedDate,
            selected: this.#state.selectedSlots,
            occupied: this.#state.occupiedSlots
        });
    }

    #updateResume() {
        this.#views.resume.display({
            selected: Array.from(this.#state.selectedSlots),
            selectedDate: this.#state.selectedDate,
        });
    }

    update() {}
}