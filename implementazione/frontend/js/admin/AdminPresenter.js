import Presenter from "../interfaces/Presenter.js"
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import { apiService } from "../utility/MockAPIService.js";

export default class AdminPresenter extends Presenter {
    constructor(view, config = {}) {
        super(view, config);
    }

    update() {

    }

    _handleViewEvents() {
        
    }
}