
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

class CartService {
    constructor() {
        this.items = [];
    }

    add(item) {
        // Simple validation or duplicate check could go here
        this.items.push(item);  
        this.notify();
    }

    remove(index) {
        if (index >= 0 && index < this.items.length) {
            this.items.splice(index, 1);
            this.notify();
        }
    }

    clear() {
        this.items = [];
        this.notify();
    }

    getItems() {
        return [...this.items];
    }

    getTotal() {
        return this.items.reduce((acc, item) => acc + item.price, 0);
    }

    notify() {
        eventBus.notify(Events.CART_UPDATED, {
            items: this.getItems(),
            total: this.getTotal()
        });
    }
}

const cartService = new CartService();
export default cartService;
