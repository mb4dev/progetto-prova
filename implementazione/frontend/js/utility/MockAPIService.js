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

	getOccupiedSlotsForWeek(startDateString) {
		const startDate = new Date(startDateString);
		startDate.setHours(0, 0, 0, 0);

		if (isNaN(startDate.getTime())) {
			return Promise.resolve(
				new Response(400, false, null, "Data non valida")
			);
		}

		const result = {};

		for (let i = 0; i < 7; i++) {
			const current = new Date(startDate);
			current.setDate(startDate.getDate() + i);

			const dateKey = current.toISOString().split("T")[0];
			const slots = this.#generateTimeSlots();
			const occupied = this.#generateOccupiedSlots(slots, dateKey);

			result[dateKey] = occupied;
		}

		return Promise.resolve(
			new Response(200, true, result, "Slot occupati recuperati con successo")
		);
	}

		#generateTimeSlots(start = 8, end = 20, increment = 30){
		const startH = start * 60;
		const endH = end * 60;
		
		const timeSlots = [];
		
		for (let h = startH; h <= endH; h+= increment){
			const hour = String(Math.floor(h/60)).padStart(2, "0") ;
			const minute = String(h % 60).padStart(2, "0");
			
			timeSlots.push(`${hour}:${minute}`);
		}
		
		return timeSlots;
	}

	#generateOccupiedSlots(slots, seed) {
		const rng = this.#seededRandom(seed);
		return slots.filter(() => rng() > 0.65);
	}
#seededRandom(seed) {
	let value = 0;

	for (let i = 0; i < seed.length; i++) {
		value += seed.charCodeAt(i);
	}

	return () => {
		value = (value * 9301 + 49297) % 233280;
		return value / 233280;
	};
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