import Presenter from "../interfaces/Presenter.js"
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import { apiService } from "../utility/MockAPIService.js";
import DatePicker from "./DatePicker.js";
import SlotPicker from "./SlotPicker.js";
import ReservationResumeView from "./ReservationResumeView.js";

export default class CalendarMediator extends Presenter {
    #views = {};
    #state = {
        week: [],
        selectedDate: null,
        selectedSlots: new Set(),
        occupiedSlots: []
    };

    constructor(view) {
        super(view);
        this.#state.week = this.#getWeekDays();
    }

    _handleViewEvents() {
        // Inizializzazione componenti quando la view principale è pronta
        eventBus.subscribe(Events.CALENDAR_LOAD_EVENT, () => {
            this.#initComponents();
        });

        // Gestione cambio data
        eventBus.subscribe(Events.DATE_SELECTED_EVENT, (data) => {
            this.#state.selectedDate = data.selectedDate;
            this.#state.selectedSlots.clear();
            this.#fetchOccupiedSlots(data.selectedDate);
        });

        // Gestione navigazione settimanale
        eventBus.subscribe(Events.DATE_INCREMENT_EVENT, () => this.#changeWeek(7));
        eventBus.subscribe(Events.DATE_DECREMENT_EVENT, () => this.#changeWeek(-7));

        // Gestione selezione slot
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

    #changeWeek(days) {
        const firstDay = new Date(this.#state.week[0].fullDate);
        firstDay.setDate(firstDay.getDate() + days);

        if (days < 0) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (firstDay < today) return;
        }

        this.#state.week = this.#getWeekDays(firstDay);
        this.#updateDatePicker();
    }

    #fetchOccupiedSlots(date) {
        apiService.getOccupiedSlotsForWeek(date).then(result => {
            this.#state.occupiedSlots = result.data[date] || [];
            this.#updateSlotPicker();
            this.#updateResume();
        });
    }

    #updateDatePicker() {
        this.#views.date.display({ week: this.#state.week });
    }

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
            // Qui potresti aggiungere altri dati come lo sport selezionato dal caricamento iniziale
        });
    }

    #getWeekDays(fromDate = new Date(), daysToShow = 7) {
        const options = { weekday: "long" };
        const days = [];
        for (let i = 0; i < daysToShow; i++) {
            const date = new Date(fromDate);
            date.setDate(date.getDate() + i);
            days.push({
                giorno: date.toLocaleDateString("it-IT", options),
                numero: String(date.getDate()).padStart(2, '0'),
                mese: String(date.getMonth() + 1).padStart(2, '0'),
                fullDate: date.toISOString().split('T')[0],
            });
        }
        return days;
    }

    update() {}
}
