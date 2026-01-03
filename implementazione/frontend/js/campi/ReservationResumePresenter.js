import Presenter from "../interfaces/Presenter.js"
import { eventBus} from "../utility/DefaultObserver.js"
import Events from "../utility/Events.js"
import {apiService} from "../utility/MockAPIService.js"
import Routes from "../utility/Routes.js"

export default class ReservationResumePresenter extends Presenter {
    #selected;
    constructor(view){
        super(view);

        this.#selected = new Set();
        
    }

    update(){
        this._view.display({ selected: this.#selected })
    }

    _handleViewEvents(){
        eventBus.subscribe(Events.SLOT_SELECTED_EVENT, time => {
            this.#selected.add(time)
            this.update()
        })

    }
}
