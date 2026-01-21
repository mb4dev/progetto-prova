import Presenter from "../interfaces/Presenter.js";
import HistoryLoadStrategy from "../strategy/HistoryLoadStrategy.js";
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class HistoryPresenter extends Presenter {
    constructor(view, config = {}) {
        if(!config.loadStrategy || !config.onDeleteCommand) throw new Error("Configurazioen minima mancante");
        super(view, config);
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

            });
    }

    _handleViewEvents() {
        eventBus.subscribe(Events.HISTORY_LOAD_EVENT, () => {
            if (!this._view.isConnected) return;
            this.update();
        });

        eventBus.subscribe(Events.HISTORY_DELETE_EVENT, (data) => {
            this._config.onDeleteCommand.execute(data.id);
        })
    }
}

