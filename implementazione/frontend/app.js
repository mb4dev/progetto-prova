import Events from "./js/utility/Events.js"
import Routes from "./js/utility/Routes.js"
import { eventBus } from "./js/utility/DefaultObserver.js"
import ViewFactory from "./js/utility/ViewFactory.js";

class App {
	#root

	constructor(root){
		if(!root) throw new Error("root non può essere null");
		if(!(root instanceof HTMLElement)) throw new Error("root deve essere un HTMLElement")

		this.#root = root;
	}

	start(){
		eventBus.subscribe(Events.ROUTING_EVENT, data => {
			this.router(data.route)
		})
		
		this.router(Routes.LOGIN);
	}

	router(route){
		this.#root.innerHTML = ""

		const components = ViewFactory.createView(route);
		if(!components) {
			console.error(`Errore creazione view di tipo ${route}`);
			return;
		}

		this.#root.appendChild(components.view);
	}
}



document.addEventListener("DOMContentLoaded", () => {

    const root = document.getElementById("app")
    const app = new App(root)

    app.start()


})
