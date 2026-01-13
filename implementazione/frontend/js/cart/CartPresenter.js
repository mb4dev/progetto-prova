import Presenter from "../interfaces/Presenter.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import cartService from "./CartService.js";
import reservationState from "../prenotazione/ReservationState.js";

export default class CartPresenter extends Presenter {
    
    constructor(view, config) {
        super(view, config);
        this.cartService = cartService;
        this.reservationState = reservationState;
    }

    _handleViewEvents() {

        eventBus.subscribe(Events.CART_UPDATED, (data) => {
            if (!this._view.isConnected) return;
            this.#updateView();
        });

        eventBus.subscribe(Events.CART_REMOVE, (data) => {
            if (!this._view.isConnected) return;
            this.cartService.remove(data.index);
        });

        eventBus.subscribe("cart:clear", () => {
            if (!this._view.isConnected) return;
            this.cartService.clear();
        });


        eventBus.subscribe(Events.SLOT_SELECTED_EVENT, (data) => {
            if (!this._view.isConnected) return;
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
        const { selectedSport, selectedDate, selectedSlots } = this.reservationState;
        
        if (!selectedSport || !selectedDate) {
            return;
        }
        const existingIndex = this.cartService.getItems().findIndex(item => 
            item.sport.title === selectedSport.title && item.date === selectedDate
        );

        if (selectedSlots.size === 0) {
            if (existingIndex !== -1) {
                this.cartService.remove(existingIndex);
            }
        } else {
            const slots = Array.from(selectedSlots);
            const price = (slots.length * selectedSport.price) / 2;
            
            const cartItem = {
                sport: selectedSport,
                date: selectedDate,
                slots: slots,
                price: price
            };

            if (existingIndex !== -1) {
                this.cartService.items[existingIndex] = cartItem;
                this.cartService.notify();
            } else {
                this.cartService.add(cartItem);
            }
        }
    }

    #updateView() {
        const cartItems = this.cartService.getItems();
        const cartTotal = this.cartService.getTotal();

        this._view.display({
            cartItems: cartItems,
            cartTotal: cartTotal
        });
    }

    update() {
        this.#updateView();
    }
}
