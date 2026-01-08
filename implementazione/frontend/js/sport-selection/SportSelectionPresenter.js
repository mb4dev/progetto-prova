import Presenter from "../interfaces/Presenter.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

export default class SportSelectionPresenter extends Presenter {

    
    constructor(view, config) {
        if (!config.loadStrategy || !config.onSelectedCommand) throw new Error("Configurazione minima SportSelectionPresenter non presente");
        super(view, config);
    }
    
    update(){}
    
    _handleViewEvents(){
        eventBus.subscribe(Events.SPORTS_LOAD_EVENT, data => {
            this._config.loadStrategy.load().then(result => {
                this._view.display({items: result.data});
            }
            );
        })
        
        eventBus.subscribe(Events.SPORT_SELECTED_EVENT, () => {
            this._config.onSelectedCommand.execute()
        });
        
    }
}