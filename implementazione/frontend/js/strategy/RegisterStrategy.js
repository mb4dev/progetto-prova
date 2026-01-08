import AuthStrategy from "../interfaces/AuthStrategy.js";
import { apiService } from "../utility/MockAPIService.js";

export default class RegisterStrategy extends AuthStrategy {
    authenticate(data) {
        return apiService.register(data.name, data.email, data.password);
    }

    validate(data) {
        if(!data) throw new Error("dati inseriti non validi")
        if(!data.email || !data.password) throw new Error("email o password sono vuoti");
        if(!data.name || !data.passwordConfirm) throw new Error("nome o conferma password sono vuoti");
        if(data.password !== data.passwordConfirm) throw new Error("le password non corrispondono");
        return true;
    }
}
