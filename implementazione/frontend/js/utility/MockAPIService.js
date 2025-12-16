import APIService from "../interfaces/APIService.js"

export class SuccessAPIService extends APIService {
    constructor() {
        super();
    }

	login(email, password) {
		const data = {
			token: "token",
			user: {
				id: 	1,
				name: 	"name",
				email: 	"email",
				admin: 	false
			}
		}
		return new Promise((resolve, reject) => {
			resolve(new Response(200, true, data, "Login effettuato con successo"))
		})
	}

	register(name, email, password) {
		const data = {
			token: "token",
			user: {
				id: 		1,
				name: 		"name",
				email: 		"email",
				admin: 		false
			}
		}
		return new Promise((resolve, reject) => {
			resolve(new Response(200, true, data, "Registrazione effettuata con successo"))
		})
	}
}

export class ErrorAPIService extends APIService {
    login(email, password) {
        return new Promise((resolve, reject) => {
            reject(new Response(400, false, null, "Credenziali non valide"));
        });
    }

    register(name, email, password) {
        return new Promise((resolve, reject) => {
            reject(new Response(409, false, null, "Utente già registrato")); 	
        });
    }
}

class Response {
	constructor(code, success, data, message) {
		this.code = code;
		this.success = success;
		this.data = data;
		this.message = message;
	}
}