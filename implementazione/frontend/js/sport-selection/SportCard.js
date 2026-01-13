import View from "../interfaces/View.js"
import Events from "../utility/Events.js"
import { eventBus } from "../utility/DefaultObserver.js"
import reservationState from "../prenotazione/ReservationState.js"

export default class SportCard extends View {

	#id
	#title
	#image
	#price
	#unit
	#label
	#event

	constructor(){
		super();
	}

	connectedCallback(){
		this.#id = this.getAttribute("data-id") || "";
		this.#title = this.getAttribute("data-title") || this.getAttribute("data-sport") || "";
		this.#image = this.getAttribute("data-image") || "";
		this.#price = this.getAttribute("data-price") || "";
		this.#unit = this.getAttribute("data-unit") || "€/h";
		this.#label = this.getAttribute("data-label") || "Prenota ora";
		this.#event = Events.SPORT_SELECTED_EVENT;

		this.innerHTML = this.template();

		this._bindEvents();
	}
	
	template(){
		return `
			<div class="group w-full border-1 border-[var(--bg-light)] bg-[var(--bg-dark)] aspect-square rounded-3xl relative shadow-md cursor-pointer hover:border-[var(--accent)] hover:shadow-xl transition-all duration-300 overflow-hidden">
				<img class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all duration-300" src=${this.#image}>	
				
				<div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-70 group-hover:opacity-90 transition-all duration-300"></div>
		
				<div class="absolute top-4 right-4 bg-[var(--accent)] text-white px-3 py-1.5 rounded-full font-bold text-sm shadow-2xl opacity-0 group-hover:opacity-100 transition-all ">
					${this.#price}${this.#unit}
				</div>

				<div class="absolute w-full start-0 bottom-0 p-6 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
					<div class="flex justify-between items-center">
						<h3 class="text-2xl font-bold capitalize text-white flex-1">${this.#title}</h3>
						
						<span class="rounded-full mt-3 w-10 h-10 bg-[var(--accent)] opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all duration-300 flex items-center justify-center">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /> </svg>
						</span>
					</div>
					<p class="text-[var(--accent)] opacity-0 group-hover:opacity-100 transition-opacity duration-300">${this.#label}</p>
				</div>
			</div>
		`
	}
	
	_bindEvents(){
		this.addEventListener("click", () => {
			
			reservationState.startNewReservation({
				id: this.#id,
				title: this.#title,
				price: this.#price,
				unit: this.#unit
			});

			eventBus.notify(this.#event);
		})
	}
	
}

customElements.define("sport-card", SportCard);