import View from "../interfaces/View.js"
import Events from "../utility/Events.js"
import { eventBus } from "../utility/DefaultObserver.js"

export default class ReservationResumeView extends View {

	#goToPaymentBtn
	#clearBtn
	
	constructor(){
		super()
	}
	
	connectedCallback(){
		this.style.display = "contents";
		this.innerHTML = this.template()
		
		this.#goToPaymentBtn = this.querySelector("#payment-btn")
		this.#clearBtn = this.querySelector("#clear-btn")
		this._bindEvents();
	}
	
	
	display(data) {
		console.log(data)
		if (data.selected) {
			const container = this.querySelector("#resume-selected-slot");
			if (container) {
				container.innerHTML = data.selected.map(time => {
					return `<div class="bg-[var(--accent)] text-[var(--text-secondary)] p-2 text-center rounded-xl shadow-xl/20 font-bold">${time}</div>`
				}).join("");
			}

			const total = this.querySelector("#resume-total");
			if (total && data.selectedSport) {
				total.textContent = (data.selected.length * data.selectedSport.price / 2).toFixed(2); 
			}
		}

		if (data.selectedDate) {
			const dayLabel = this.querySelector("#resume-selected-day");
			if (dayLabel) {
				dayLabel.textContent = data.selectedDate.split("-").reverse().join("/");
			}
		}

		if(data.selectedSport){
			const sportLabel = this.querySelector("#resume-selected-sport");
			if(sportLabel){
				sportLabel.textContent = data.selectedSport.title
			}
		}
	}
	
	template(){
		return `
			<div class="flex flex-col h-full px-4 pb-4 rounded-xl >
				<div class="flex flex-col flex-1">
					<div class="p-4 pb-3 flex justify-center items-center">
						<h1 class="text-lg font-semibold flex items-center gap-2 text-[var(--accent)]">
							Dettagli prenotazione
						</h1>
					</div>

					<div class="p-2">
						<p class="font-bold tracking-wider">Sport selezionato</p>
						<p class="text-center p-3 text-xl font-bold" id="resume-selected-sport"></p>
					</div>

					<div class="p-2">
						<p class="font-bold tracking-wider">Giorno selezionato</p>
						<p class="text-center p-3 text-xl font-bold" id="resume-selected-day"></p>
					</div>
					
					<div class="p-2	">
						<p class="font-bold tracking-wider">Orari selezionati</p>
						<div class="grid grid-cols-4 gap-4 p-4 items-center" id="resume-selected-slot">
						</div>

					</div>

					<div class="p-2">
						<p class="font-bold tracking-wider">Totale</p>
						<p><span class="text-center p-3 text-xl font-bold" id="resume-total"></span>€</p>
					</div>

					<div class="flex flex-row mt-auto w-full gap-3 ">

					<button
						id="clear-btn"
						class="w-full p-3 bg-[var(--bg-med)] shadow-lg/20 text-[var(--text-primary)] font-bold rounded-xl hover:scale-102 cursor-pointer hover:shadow-xl/25 hover:bg-[var(--accent)] transition-all duration-150 ease-in-out">
						Annulla
					</button>

					<button
						id="payment-btn"
						class="w-full p-3 bg-[var(--bg-med)] shadow-lg/20 text-[var(--text-primary)] font-bold rounded-xl hover:scale-102 cursor-pointer hover:shadow-xl/25 hover:bg-[var(--accent)] transition-all duration-150 ease-in-out">
						Vai al pagamento
					</button>

					<div>
				</div>
			</div>
		`
		
	}
	
	_bindEvents(){
		this.#clearBtn.addEventListener("click", () => {
			eventBus.notify(Events.RESUME_CLEAR);
		})
		
		this.#goToPaymentBtn.addEventListener("click", () => {
			eventBus.notify(Events.PAYMENT_PROCEED_EVENT)
		})

	}
	
}

customElements.define("reservation-resume-view", ReservationResumeView)