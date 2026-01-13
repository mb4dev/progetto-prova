import Presenter from "../interfaces/Presenter.js";
import { eventBus } from "../utility/DefaultObserver.js";
import cartService from "../cart/CartService.js";
import Events from "../utility/Events.js";

export default class PaymentPresenter extends Presenter{
	constructor(view, config){
		super(view, config);
	}

	init(){
		super.init();
		// Wait for the view to be fully rendered before updating
		requestAnimationFrame(() => {
			this.update();
		});
	}

	update(){
		const cartItems = cartService.getItems();
		const cartTotal = cartService.getTotal();
		
		this._view.display({
			cartItems,
			cartTotal
		});
	}

	_handleViewEvents(){
		// Listen to cart updates to refresh the payment view
		eventBus.subscribe(Events.CART_UPDATED, () => {
			if (!this._view.isConnected) return;
			this.update();
		});
	}
}