import Presenter from "../interfaces/Presenter.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import cartService from "./CartService.js";
import reservationState from "../prenotazione/ReservationState.js";

export default class CartPresenter extends Presenter {
    
    constructor(view, config) {
        super(view, config);
    }

    _handleViewEvents() {

        eventBus.subscribe(Events.CART_UPDATED, () => {
            if (!this._view.isConnected) return;
            this.#updateView();
        });

        eventBus.subscribe(Events.CART_REMOVE, (data) => {
            if (!this._view.isConnected) return;
            cartService.remove(data.index);
        });

        eventBus.subscribe(Events.CART_CLEAR, () => {
            if (!this._view.isConnected) return;
            cartService.clear();
        });

        eventBus.subscribe(Events.CART_REMOVE_SUBSCRIPTION, () => {
            if (!this._view.isConnected) return;
            cartService.removeSubscription();
        });


        eventBus.subscribe(Events.SLOT_SELECTED_EVENT, (data) => {
            if (!this._view.isConnected) return;
            // Delay to ensure state is updated by CalendarPresenter
            setTimeout(() => {
                this.#addToCart();
            }, 50);
        });

        eventBus.subscribe(Events.DATE_SELECTED_EVENT, () => {
            if (!this._view.isConnected) return;
            this.#updateView();
        });

        eventBus.subscribe(Events.RESUME_CLEAR, () => {
            if (!this._view.isConnected) return;
            this.#updateView();
        });
    }

    #addToCart() {
        const { selectedSport, selectedDate, selectedSlots } = reservationState;
        
        if (!selectedSport || !selectedDate) {
            return;
        }

        const existingIndex = cartService.items.findIndex(item => 
            item.sport?.title === selectedSport.title && item.date === selectedDate
        );

        if (selectedSlots.size === 0) {
            if (existingIndex !== -1) {
                cartService.remove(existingIndex);
            }
        } else {
            const slots = Array.from(selectedSlots);
            const price = (slots.length * parseFloat(selectedSport.price)) / 2;
            
            const cartItem = {
                type: 'field',
                sport: selectedSport,
                date: selectedDate,
                slots: slots,
                price: price
            };

            if (existingIndex !== -1) {
                cartService.items[existingIndex] = cartItem;
                cartService.notify();
            } else {
                cartService.add(cartItem);
            }
        }
    }

    #updateView() {
        const regularItems = cartService.items;
        const subscriptions = cartService.subscriptions;
        const cartTotal = cartService.getTotal();

        this._view.display({
            cartItems: regularItems,
            subscriptions: subscriptions,
            cartTotal: cartTotal
        });
    }

    update() {
        this.#updateView();
    }
}
