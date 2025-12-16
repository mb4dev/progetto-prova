import Events from "./js/utility/Events.js"
import Routes from "./js/utility/Routes.js"
import LoginView from "./js/auth/LoginView.js"
import RegisterView from "./js/auth/RegisterView.js"
import AuthPresenter from "./js/auth/AuthPresenter.js"
import APIService from "./js/interfaces/APIService.js"
import {SuccessAPIService, ErrorAPIService} from "./js/utility/MockAPIService.js"

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
		document.addEventListener(Events.ROUTING_EVENT, (e) => {
			this.router(e.detail.route)
		})
		
		this.router(Routes.LOGIN);
	}

	router(route){
		this.#root.innerHTML = ""

		var view = null;
	
		switch (route) {
			case Routes.LOGIN : 
				view = new LoginView();
				this.#root.appendChild(view);
				break;

			case Routes.REGISTER:
				view = new RegisterView()
				this.#root.appendChild(view);
				break;
		}

		const presenter = new AuthPresenter(view, this.#service);
		presenter.init();
	}
}



document.addEventListener("DOMContentLoaded", () => {
    const root = document.getElementById("app")

	const service = new ErrorAPIService()
    const app = new App(root, service)

    app.start()

})
	











