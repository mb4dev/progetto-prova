import Presenter from "../Presenter.js"
import Events from "../../Events.js";
import LoginView from "./LoginView.js"
import LoginPresenter from "./LoginPresenter.js"

export default class RegisterPresenter extends Presenter{
	constructor(view) {
		super(view)
		
		this._registerEvents()
	}
	
	_registerEvents(){
		this.#handleSubmit();
		this.#handleLoginRoutingEvent()
	}
	
	#handleSubmit(){
		this._view.addEventListener(Events.REGISTER_SUBMIT_EVENT, (e) => {
			console.log(e.detail)
		})
	}

	#handleLoginRoutingEvent(){
        this._view.addEventListener(Events.LOGIN_ROUNTING_EVENT, (e) => {
			const app = document.getElementById("app")
			app.innerHTML = ""
			const view = new LoginView()

			const presenter = new LoginPresenter(view)
			app.appendChild(view)
        })
    }
}
