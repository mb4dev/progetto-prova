import Events from "./js/utility/Events.js"
import Routes from "./js/utility/Routes.js"
import LoginView from "./js/auth/LoginView.js"
import RegisterView from "./js/auth/RegisterView.js"
import AuthPresenter from "./js/auth/AuthPresenter.js"
import APIService from "./js/interfaces/APIService.js"
import { eventBus } from "./js/utility/DefaultObserver.js"
import { SuccessAPIService } from "./js/utility/MockAPIService.js"
import MainPresenter from "./js/main/MainPresenter.js"
import MainView from "./js/main/MainView.js"

class App {
	#root
	#service

	constructor(root, service){
		if(!root) throw new Error("root non può essere null");
		if(!(root instanceof HTMLElement)) throw new Error("root deve essere un HTMLElement")

		if(!service) throw new Error("service non può essere null");
		if(!(service instanceof APIService)) throw new Error("service deve essere un APIService")

		this.#root = root;
		this.#service = service;
	}

	start(){
		eventBus.subscribe(Events.ROUTING_EVENT, data => {
			this.router(data.route)
		})
		
		this.router(Routes.MAIN);
	}

	router(route){
		this.#root.innerHTML = ""

		var view = null;
		var presenter = null;
		switch (route) {
			case Routes.LOGIN : 
				view = new LoginView();
				this.#root.appendChild(view);
				presenter = new AuthPresenter(view, this.#service);
				break;

			case Routes.REGISTER:
				view = new RegisterView()
				this.#root.appendChild(view);
				presenter = new AuthPresenter(view, this.#service);
				break;

			case Routes.MAIN:
				view = new MainView()
				this.#root.appendChild(view);
				presenter = new MainPresenter(view, this.#service);
				break;
		}
		presenter.init();
	}
}



document.addEventListener("DOMContentLoaded", () => {

	
    const root = document.getElementById("app")

	const service = new SuccessAPIService()
    const app = new App(root, service)

    app.start()

})
	











