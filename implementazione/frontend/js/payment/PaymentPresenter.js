import Presenter from "../interfaces/Presenter.js";

export default class PaymentPresenter extends Presenter {
    
    constructor(view, data) {
        super(view);
        this._data = data;
        
        // If data is immediately available, update the view
        if(this._data){
            this._view.display(this._data);
        }
    }


    _handleViewEvents(){
        // Handlers for view events will go here
    }

    update(){
        // Update logic will go here
    }
}
