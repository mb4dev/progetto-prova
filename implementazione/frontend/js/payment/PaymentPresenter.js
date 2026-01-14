import Presenter from "../interfaces/Presenter.js";
import { eventBus } from "../utility/DefaultObserver.js";
import cartService from "../cart/CartService.js";
import Events from "../utility/Events.js";
import CartView from "../cart/CartView.js";
import CartPresenter from "../cart/CartPresenter.js";

export default class PaymentPresenter extends Presenter{

	#cartView;
	#cartPresenter;

	constructor(view, config){
		if (!config || !config.paymentStrategy) {
			throw new Error("Configurazione minima PaymentPresenter non presente (paymentStrategy mancante)");
		}
		super(view, config);
		
		this.#cartView = new CartView();
		this.#cartView.hideCheckout = true;
		this.#cartPresenter = new CartPresenter(this.#cartView, {});

		requestAnimationFrame(() => {
			this.update();
			this.#cartPresenter.update();
		});
	}

	update(){
		const cartItems = cartService.getItems();
		const cartTotal = cartService.getTotal();
		
		this._view.display({
			cartItems,
			cartTotal,
			cart: this.#cartView
		});
	}

	_handleViewEvents(){

		eventBus.subscribe(Events.CART_UPDATED, () => {
			if (!this._view.isConnected) return;
			this.update();
		});

		eventBus.subscribe(Events.PAYMENT_CONFIRM_EVENT, () => {
			if (!this._view.isConnected) return;

			const payload = {
				items: cartService.getItems(),
				total: cartService.getTotal()
			};

			this._config.paymentStrategy.pay(payload)
				/*
				.then((response) => {
					console.log("Pagamento effettuato:", response);
					
					if (payload.items && payload.items.length) {
						cartService.clear();
					}
					this._config.onConfirmPaymentCommand?.execute({ response });
				})
				.catch((error) => {
					console.error("Errore durante il pagamento:", error);
				});
				*/
		});
	}
}