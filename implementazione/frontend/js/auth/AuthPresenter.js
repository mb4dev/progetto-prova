import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class AuthPresenter extends Presenter {
    constructor(view, config) {
        if (!config.authStrategy) throw new Error("AuthStrategy non presente nella configurazione");
        super(view, config);
    }

    _handleViewEvents() {
        this._handleSubmit();
        this._handleRouting();
    }

    _handleSubmit() {
        eventBus.subscribe(Events.AUTH_SUBMIT_EVENT, (data) => {
            try {
                this._config.authStrategy.validate(data);
                
                this._config.authStrategy.authenticate(data)
                    .then((response) => {
                        if (response.success === false) throw new Error(response.message);
                        
                        if (this._config.onSuccessCommand) {
                            this._config.onSuccessCommand.execute();
                        }
                    })
                    .catch((error) => {
                        this._view.display({ error: error.message });
                    });
            }
            catch (error) {
                this._view.display({ error: error.message });
            }
        });
    }

    _handleRouting() {
        eventBus.subscribe(Events.AUTH_NAVIGATE_EVENT, () => {
            if (this._config.onNavigateCommand) {
                this._config.onNavigateCommand.execute();
            }
        });
    }

    update() { }
}
