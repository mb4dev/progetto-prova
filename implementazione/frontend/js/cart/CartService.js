
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

class CartService {
    
    constructor() {
        this.items = [];
        this.subscriptions = [];

    }

    add(item) {
        console.log(item)
        if (item.type === 'subscription') {
            this.subscriptions = [];
            this.subscriptions.push(item);
        } else {
            this.items.push(item);
        }
        this.notify();
    }

    remove(index) {
        if (index >= 0 && index < this.items.length) {
            this.items.splice(index, 1);
            this.notify();
        }
    }

    removeSubscription() {
        this.subscriptions = [];
        this.notify();
    }

    clear() {
        this.items = [];
        this.subscriptions = [];
        this.notify();
    }

    getItems() {
        return [...this.items, ...this.subscriptions];
    }

    getTotal() {
        const itemsTotal = this.items.reduce((acc, item) => acc + item.price, 0);
        const subscriptionsTotal = this.subscriptions.reduce((acc, sub) => acc + sub.price, 0);
        return itemsTotal + subscriptionsTotal;
    }

    notify() {
        eventBus.notify(Events.CART_UPDATED, {
            items: this.getItems(),
            subscriptions: this.subscriptions,
            total: this.getTotal()
        });
    }
}

const cartService = new CartService();
export default cartService;
