import Presenter from "../interfaces/Presenter.js"
import Events from "../utility/Events.js";
import LoginView from "./LoginView.js";
import RegisterView from "./RegisterView.js";

export default class AuthPresenter extends Presenter{
    constructor(view) {
        super(view)
    }

    _handleViewEvents(){
        this.#handleSubmit();
    }

	#handleSubmit(){
		this._view.addEventListener(Events.AUTH_SUBMIT_EVENT, (e) => {
            const data = this.#validateInput(e.detail)
            console.log(data)

            this.#callApi(data);
        })
	}

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

        //TODO inserire la logica per la chiamata API
        if(this._view instanceof LoginView){

        }
        else {

        }
    }

}
