import Presenter from "../interfaces/Presenter.js"
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import DatePicker from "./DatePicker.js";
import SlotPicker from "./SlotPicker.js";
import CartView from "../cart/CartView.js";
import CartPresenter from "../cart/CartPresenter.js";
import reservationState from "./ReservationState.js";

export default class CalendarPresenterV2 extends Presenter {
    #views = {};
    #state

    constructor(view, config) {
        if(!config.loadStrategy || !config.onConfirmCommand) throw new Error("Configurazione minima mancante");
        super(view, config);

        this.#state = reservationState
    }

    _handleViewEvents() {
        eventBus.subscribe(Events.CALENDAR_LOAD_EVENT, () => {
            if (!this._view.isConnected) return;
            this.#initComponents();
        });

        eventBus.subscribe(Events.DATE_SELECTED_EVENT, (data) => {
            if (!this._view.isConnected) return;
            this.#state.selectedDate = data.selectedDate;
            this.#state.selectedSlots.clear();

            this._config.loadStrategy.load({
                date: data.selectedDate,
                resourceId: this.#state.selectedSport.id,
                resourceType: this.#state.selectedSport.type
            }).then(result => {
                console.log("caricati dati per la data ", data.selectedDate)
                const allSlots = result.data || {};
                this.#state.occupiedSlots = allSlots[data.selectedDate] || []; 
                this.#updateSlotPicker();
            });
        });

        eventBus.subscribe(Events.DATE_INCREMENT_EVENT, () => {
            if (!this._view.isConnected) return;
            this.#state.changeWeek(7,this.#updateDatePicker())
        });
        
        eventBus.subscribe(Events.DATE_DECREMENT_EVENT, () => {
            if (!this._view.isConnected) return;
            this.#state.changeWeek(-7, this.#updateDatePicker())
        });

        eventBus.subscribe(Events.SLOT_SELECTED_EVENT, (data) => {
            if (!this._view.isConnected) return;
            const { time } = data;
            if (this.#state.selectedSlots.has(time)) {
                this.#state.selectedSlots.delete(time);
            } else {
                this.#state.selectedSlots.add(time);
            }
            this.#updateSlotPicker();
        });

        eventBus.subscribe(Events.RESUME_CLEAR, () => {
            if (!this._view.isConnected) return;
            this.#state.clear();
            this.#updateSlotPicker();
        })

        eventBus.subscribe(Events.PAYMENT_PROCEED_EVENT, () => {
            if (!this._view.isConnected) return;
            this._config.onConfirmCommand.execute();
        })
    }

    #initComponents() {
        this.#views.date = new DatePicker();
        this.#views.slot = new SlotPicker();
        this.#views.cart = new CartView();

        this.#views.cartPresenter = new CartPresenter(this.#views.cart, {});

        this._view.display({
            date: this.#views.date,
            slot: this.#views.slot,
            cart: this.#views.cart
        });


        requestAnimationFrame(() => {
            this.#updateDatePicker();
            this.#views.cartPresenter.update();
        });
    }

    #updateDatePicker() {
        this.#views.date.display({ week: this.#state.week });
    }
1
    #updateSlotPicker() {
        const slots = this.#state.selectedSport.type === "course" ? this.#state.selectedSport.schedule : null;

        this.#views.slot.display({
            selectedDate: this.#state.selectedDate,
            selected: this.#state.selectedSlots,
            occupied: this.#state.occupiedSlots,
            slots: slots
        });
    }



    update() {}
}