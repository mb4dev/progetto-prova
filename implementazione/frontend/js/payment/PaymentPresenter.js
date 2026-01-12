import Presenter from "../interfaces/Presenter.js";
import { eventBus } from "../utility/DefaultObserver.js";
import cartService from "../cart/CartService.js";

export default class PaymentPresenter extends Presenter{
	constructor(view, config){
		super(view, config);
	}

	update(){

	}

	_handleViewEvents(){

		

	}
}