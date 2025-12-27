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

    getProfile() {
        const data = {
            id: 1,
            name: "John Doe",
            email: "john.doe@example.com",
            admin: false
        }
        return new Promise((resolve) => {
            resolve(new Response(200, true, data, "Profilo recuperato con successo"));
        });
    }

    updateProfile(profileData) {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve(new Response(200, true, profileData, "Profilo aggiornato con successo"));
            }, 500);
        });
    }

	getSports() {
		const data = [
			{id: 1, name: "calcio", img: "assets/images/calcio.png", price:100},
			{id: 2, name: "tennis", img: "assets/images/tennis.png", price:70},
			{id: 4, name: "basket", img: "assets/images/basket.png", price:50},
			{id: 5, name: "padel", img: "assets/images/padel.png", price:60}];
					
		return new Promise((resolve) => {
			resolve(new Response(200, true, data, "Sport recuperati con successo"));
		});
	}
}

export const apiService = new SuccessAPIService();

class Response {
	constructor(code, success, data, message) {
		this.code = code;
		this.success = success;
		this.data = data;
		this.message = message;
	}
}