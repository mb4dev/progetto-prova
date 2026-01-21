import AuthStrategy from "../interfaces/AuthStrategy.js"
import { eventBus } from "../utility/DefaultObserver.js";
import { apiService } from "../utility/MockAPIService.js";

export default class LoginStrategy extends AuthStrategy {

	async authenticate(data) {
		try {
			const result = await apiService.login(data.email, data.password)
			//console.log(result)
			if(!result.success) throw new Error(result.message); 

			localStorage.setItem("user",  JSON.stringify(result.data.user))
		}
		catch(e){
			throw new Error(e); 
		}
	}

	validate(data) {
		if(!data) throw new Error("dati inseriti non validi");

		if(!data.email) throw new Error("Il campo email non può essere vuoto");
		if(!data.password) throw new Error("Il campo password non può essere vuoto");
	}
	
}