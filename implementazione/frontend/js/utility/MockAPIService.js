import APIService from "../interfaces/APIService.js"
import ItemType from "./ItemType.js"

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
			resolve(new Response(200, true, data, "Login"))
		})
	}
	
	register(name, email, password) {
		const data = {
			token: "token",
			user: {
				id: 		1,
				name: 		"nome cognome",
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
			{id: 3, name: "basket", img: "assets/images/basket.png", price:50},
			{id: 4, name: "padel", img: "assets/images/padel.png", price:60}];
			
			return new Promise((resolve) => {
				resolve(new Response(200, true, data, "Sport recuperati con successo"));
			});
		}

	
	getCourses(){
		const data = [
			{id: 1, name: "pesi", img: "", price:100, schedule: ["09:00", "11:00", "15:00"]},
			{id: 2, name: "palestra", img: "", price:70, schedule: ["18:00", "20:00"]},
			{id: 3, name: "boxe", img: "", price:50, schedule: ["08:00", "10:00", "13:00", "19:00"]},
			{id: 4, name: "yoga", img: "	", price:60, schedule: ["07:00", "19:30"]}];
			
			return new Promise((resolve) => {
				resolve(new Response(200, true, data, "Corsi recuperati con successo"));
			});
		}
	
	getSubscriptions() {
		const data = [
			{ id: 1, name: "Mensile", description: "Accesso illimitato per 30 giorni", price: 49.90 },
			{ id: 2, name: "Trimestrale", description: "Accesso illimitato per 3 mesi", price: 129.90 },
			{ id: 3, name: "Annuale", description: "Accesso illimitato per 12 mesi", price: 399.90 },
		];
		
		return new Promise((resolve) => {
			resolve(new Response(200, true, data, "Abbonamenti recuperati con successo"));
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
	
	getHistory() {
		const today = new Date();
		const tomorrow = new Date(today);
		tomorrow.setDate(today.getDate() + 1);
		const nextWeek = new Date(today);
		nextWeek.setDate(today.getDate() + 7);
		
		const data = [
			{ id: 1, type: ItemType.FIELD, title: "Tennis", date: tomorrow.toISOString().split('T')[0], slot:["08:00", "08:30"] , amount: 70.00 },
			{ id: 2, type: ItemType.FIELD, title: "Calcio", date: nextWeek.toISOString().split('T')[0], slot:["18:00"], amount: 100.00 },
			{ id: 3, type: ItemType.SUB, title: "Abbonamento Mensile", date: "2026-01-12", slot:[], amount: 49.90 },
			{ id: 4, type: ItemType.COURSE, title: "Palestra", date: "2026-01-12", slot:["15:00"], amount: 70.00 },
		];
		
		return Promise.resolve(
			new Response(200, true, data, "Storico prenotazioni recuperato con successo")
		);
	}
	processSinglePayment(paymentData) {
		const data = {
			type: "single",
			total: paymentData?.total ?? 0,
			itemsCount: paymentData?.items?.length ?? 0,
		};
		
		return Promise.resolve(
			new Response(200, true, data, "Pagamento singolo effettuato con successo")
		);
	}

	processSubscriptionPayment(subscriptionData) {
		const data = {
			type: "subscription",
			subscriptionId: subscriptionData?.subscriptionId ?? null,
		};
		
		return Promise.resolve(
			new Response(200, true, data, "Pagamento abbonamento effettuato con successo")
		);
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