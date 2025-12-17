import Observer from "../interfaces/Observer.js";

export default class DefaultObserver extends Observer {
    constructor() {
        super();
    }

    subscribe(event, callback) {
        if (!callback || typeof callback !== "function") {
            throw new Error("callback non valida");
        }
        if (!event) {
            throw new Error("event non valido");
        }
        if (!this._listeners.has(event)) {
            this._listeners.set(event, []);
        }
        this._listeners.get(event).push(callback);
        return () => this.unsubscribe(event, callback);
    }

    unsubscribe(event, callback) {
        if (!this._listeners.has(event)) return;
        const callbacks = this._listeners.get(event);
        const index = callbacks.indexOf(callback);
        if (index !== -1) {
            callbacks.splice(index, 1);
        }
    }

    notify(event, data) {
        if (!this._listeners.has(event)) return;
        this._listeners.get(event).forEach(callback => {
            try {
                callback(data);
            } catch (err) {
                console.error(`Errore nell'observer per ${event}:`, err);
            }
        });
    }

    clear() {
        this._listeners.clear();
    }
}

export const eventBus = new DefaultObserver();
