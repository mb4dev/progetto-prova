import Presenter from "../interfaces/Presenter.js";
import { apiService } from "../utility/MockAPIService.js";
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class HistoryPresenter extends Presenter {
    constructor(view, config = {}) {
        super(view, config);
    }

    update() {
        // In una futura integrazione reale, qui verrà chiamata l'API per recuperare lo storico
        apiService
            .getHistory?.()
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
        eventBus.subscribe(Events.HISTORY_LOAD_EVENT, () => {
            if (!this._view.isConnected) return;
            this.update();
        });
    }
}

