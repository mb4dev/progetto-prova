import Presenter from "../interfaces/Presenter.js";
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Routes from "../utility/Routes.js";
import NavigateCommand from "../commands/NavigateCommand.js";

export default class SubscriptionPresenter extends Presenter {
    constructor(view, config = {}) {
        if (!config.loadStrategy) {
            throw new Error("Configurazione minima SubscriptionPresenter non presente (loadStrategy mancante)");
        }
        super(view, config);
        this._navigateToPayment =
            config.onSelectedCommand || new NavigateCommand(Routes.MAIN_PAYMENT_SUBSCRIPTION);
    }

    update() {
        this._config.loadStrategy
            .load()
            .then((response) => {
                if (!response || response.success === false || !response.data) {
                    return;
                }
                this._view.display({ items: response.data });
            })
            .catch(() => {
                // per ora ignoriamo gli errori e lasciamo la view vuota
            });
    }

    _handleViewEvents() {
        eventBus.subscribe(Events.SUBSCRIPTION_LOAD_EVENT, () => {
            if (!this._view.isConnected) return;
            this.update();
        });

        eventBus.subscribe(Events.SUBSCRIPTION_SELECTED_EVENT, (data) => {
            if (!this._view.isConnected) return;
            // In una futura versione potremmo aggiungere l'abbonamento al carrello
            this._navigateToPayment.execute();
        });
    }
}

