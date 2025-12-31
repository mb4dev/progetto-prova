import Presenter from "../interfaces/Presenter.js"
import { eventBus} from "../utility/DefaultObserver.js"
import Events from "../utility/Events.js"
import {apiService} from "../utility/MockAPIService.js"
import Routes from "../utility/Routes.js"

export default class CampiPresenter extends Presenter {
    constructor(view){
        super(view);
    }

    update(){

    }

    _handleViewEvents(){
        eventBus.subscribe(Events.FIELDS_LOAD_EVENT, () => {
            apiService.getSports().then((response) => {
                this._view.display({ fields: response.data});
            });
        });

        eventBus.subscribe(Events.SPORT_SELECTED_EVENT, (data) => {
            eventBus.notify(Events.MAIN_NAVIGATE, {main: Routes.MAIN_CALENDARIO})
        });

    }
}
