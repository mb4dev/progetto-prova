import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import LoginView from "./LoginView.js";
import RegisterView from "./RegisterView.js";

export default class AuthPresenter extends Presenter{
    constructor(view, service) {
        super(view, service)
    }

    _handleViewEvents(){
        this.#handleSubmit();
    }

	#handleSubmit(){
		this._view.addEventListener(Events.AUTH_SUBMIT_EVENT, (e) => {
            try {
                const data = this.#validateInput(e.detail)
                const response = this.#callApi(data)
                this.#handleResponse(response)
            }
            catch(error){
                this._view.display({error: error.message})
            }

        })
	}

    update(){}

    #validateInput(eventDetail){
        if(!eventDetail) throw new Error("dati inseriti non validi")
        if(!eventDetail.email || !eventDetail.password) throw new Error("email o password sono vuoti");

        var authData = {
            email:  eventDetail.email,
            password: eventDetail.password
        }

        if(this._view instanceof RegisterView){
            if(!eventDetail.name || !eventDetail.passwordConfirm) throw new Error("name o passwordConfirm sono vuoti");
            if(eventDetail.password !== eventDetail.passwordConfirm) throw new Error("le password non corrispondono");
            
            authData.name = eventDetail.name;
        }

        return authData;
    }

    #callApi(data){
        if(!data) throw new Error("dati chiamata non validi");
        
        if(this._view instanceof RegisterView){
            return this._service.register(data.name, data.email, data.password)
        }
        return this._service.login(data.email, data.password)
    }

    #handleResponse(response){
        if(!response) throw new Error("risposta non valida");

        response.then((response) => {
            if (response.success === false) throw new Error(response.message)
        }).catch((error) => {
            this._view.display({error: error.message})
        })
    }

}
