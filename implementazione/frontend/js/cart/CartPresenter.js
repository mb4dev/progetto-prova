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
        // Listen to cart updates
        eventBus.subscribe(Events.CART_UPDATED, (data) => {
            this.#updateView();
        });

        // Remove item from cart
        eventBus.subscribe("cart:remove-item", (data) => {
            this.cartService.remove(data.index);
        });

        // Clear cart
        eventBus.subscribe("cart:clear", () => {
            this.cartService.clear();
        });

        // Auto-add to cart when slots are selected
        eventBus.subscribe(Events.SLOT_SELECTED_EVENT, (data) => {
            // Add a small delay to allow state to update
            setTimeout(() => {
                this.#autoAddToCart();
            }, 50);
        });

        eventBus.subscribe(Events.DATE_SELECTED_EVENT, () => {
            this.#updateView();
        });

        eventBus.subscribe(Events.RESUME_CLEAR, () => {
            this.#updateView();
        });
    }

    #autoAddToCart() {
        const { selectedSport, selectedDate, selectedSlots } = this.reservationState;
        
        if (!selectedSport || !selectedDate) {
            return;
        }

        // Find if there's already an item in cart for this sport+date combination
        const existingIndex = this.cartService.getItems().findIndex(item => 
            item.sport.title === selectedSport.title && item.date === selectedDate
        );

        if (selectedSlots.size === 0) {
            // If no slots selected, remove the item from cart if it exists
            if (existingIndex !== -1) {
                this.cartService.remove(existingIndex);
            }
        } else {
            // Create/update cart item
            const slots = Array.from(selectedSlots);
            const price = (slots.length * selectedSport.price) / 2;
            
            const cartItem = {
                sport: selectedSport,
                date: selectedDate,
                slots: slots,
                price: price
            };

            if (existingIndex !== -1) {
                // Update existing item
                this.cartService.items[existingIndex] = cartItem;
                this.cartService.notify();
            } else {
                // Add new item
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
