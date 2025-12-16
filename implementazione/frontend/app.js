import Events from "./js/utility/Events.js"
import Routes from "./js/utility/Routes.js"
import LoginView from "./js/auth/LoginView.js"
import RegisterView from "./js/auth/RegisterView.js"
import AuthPresenter from "./js/auth/AuthPresenter.js"


class App {
	#root
	constructor(root){
		if(!root) throw new Error("root non può essere null");
		if(!(root instanceof HTMLElement)) throw new Error("root deve essere un HTMLElement")

		this.#root = root;
	}

	start(){
		document.addEventListener(Events.ROUTING_EVENT, (e) => {
			this.router(e.detail.route)
		})
		
		this.router(Routes.LOGIN);
	}

	router(route){
		this.#root.innerHTML = ""

		switch (route) {
			case Routes.LOGIN : {}
				const loginView = new LoginView();
				this.#root.appendChild(loginView);
				const loginPresenter = new AuthPresenter(loginView);
				loginPresenter.init();
				break;

			case Routes.REGISTER:
				const registerView = new RegisterView()
				this.#root.appendChild(registerView);
				const registerPresenter = new AuthPresenter(registerView)
				registerPresenter.init()
				break;
		}
	}
}




document.addEventListener("DOMContentLoaded", () => {
    const root = document.getElementById("app")
    const app = new App(root)

    app.start()
})











