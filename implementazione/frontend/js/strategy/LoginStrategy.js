<<<<<<< HEAD
import AuthStrategy from "../interfaces/AuthStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class LoginStrategy extends AuthStrategy {
    authenticate(data) {
        return apiService.login(data.email, data.password);
    }

    validate(data) {
        if(!data) throw new Error("dati inseriti non validi")
        if(!data.email || !data.password) throw new Error("email o password sono vuoti");
        return true;
    }
}
=======
import AuthStrategy from "../interfaces/AuthStrategy.js"
import { eventBus } from "../utility/DefaultObserver.js";
import { apiService } from "../utility/MockAPIService.js";

export default class LoginStrategy extends AuthStrategy {
	
	authenticate(data) {
		return apiService.login(data.email, data.password)
	}
	
	validate(data) {
		if(!data) throw new Error("dati inseriti non validi");

		if(!data.email) throw new Error("Il campo email non può essere vuoto");
		if(!data.password) throw new Error("Il campo password non può essere vuoto");
	}
	
}
>>>>>>> dev
