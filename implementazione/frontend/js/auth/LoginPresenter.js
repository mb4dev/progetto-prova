import Presenter from "../Presenter.js"
import Events from "../../Events.js"
import RegisterView from "./RegisterView.js"
import RegisterPresenter from "./RegisterPresenter.js"

export default class LoginPresenter extends Presenter{
    constructor(view) {
        super(view)
        
        this._registerEvents()
    }
    
    _registerEvents(){
        this.#handleLoginEvent();
        this.#handleRegisterRoutingEvent();
    }
    
    #handleLoginEvent(){
        this._view.addEventListener(Events.SUBMIT_LOGIN_EVENT, (e) => {
            console.log(e.detail)
        })
    }
    
    #handleRegisterRoutingEvent(){
        this._view.addEventListener(Events.REGISTER_ROUTING_EVENT, (e) => {
            const app = document.getElementById("app")
            app.innerHTML = ""
            const registerView = new RegisterView()

            const presenter = new RegisterPresenter(registerView)
            app.appendChild(registerView)

            
        })
    }
}
