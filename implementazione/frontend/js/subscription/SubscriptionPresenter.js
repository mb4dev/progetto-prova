import Presenter from "../interfaces/Presenter.js";
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Routes from "../utility/Routes.js";
import NavigateCommand from "../commands/NavigateCommand.js";
import CartView from "../cart/CartView.js";
import CartPresenter from "../cart/CartPresenter.js";
import cartService from "../cart/CartService.js";

export default class SubscriptionPresenter extends Presenter {
    #components = {};
    constructor(view, config) {
        if (!config.loadStrategy) {
            throw new Error("Configurazione minima SubscriptionPresenter non presente (loadStrategy mancante)");
        }
        super(view, config);

    }

    update() {
        this.#components.cart = new CartView();
        this.#components.cartPresenter = new CartPresenter(this.#components.cart, {});

        this._config.loadStrategy
            .load()
            .then((response) => {
                if (!response || response.success === false || !response.data) {
                    return;
                }
                this._view.display({ cart: this.#components.cart, items: response.data });
            })
    }

    _handleViewEvents() {
        eventBus.subscribe(Events.SUBSCRIPTION_LOAD_EVENT, () => {
            if (!this._view.isConnected) return;
            this.update();
        });

        eventBus.subscribe(Events.SUBSCRIPTION_SELECTED_EVENT, (data) => {
            if (!this._view.isConnected) return;

            
            //this._navigateToPayment.execute();
        });
    }
}

