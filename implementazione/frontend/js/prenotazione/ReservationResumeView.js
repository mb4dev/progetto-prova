	import View from "../interfaces/View.js"
	
	import Events from "../utility/Events.js"
	import { eventBus } from "../utility/DefaultObserver.js"
	
	export default class ReservationResumeView extends View {
		
		constructor(){
			super()
		}
		
		connectedCallback(){
			this.style.display = "contents";
			this.innerHTML = this.template()
			this._bindEvents();

		}
		
		
		display(data) {
			if (data.selected) {
				const container = this.querySelector("#resume-selected");
				if (container) {
					container.innerHTML = data.selected.map(time => {
						return `<div class="bg-[var(--accent)] text-[var(--text-secondary)] p-2 text-center rounded-xl shadow-xl/20 font-bold">${time}</div>`
					}).join("");
				}

				const total = this.querySelector("#resume-total");
				if (total) {
					total.textContent = (data.selected.length * 15).toFixed(2); // Esempio 15€ a slot
				}
			}

			if (data.selectedDate) {
				const dayLabel = this.querySelector("#resume-selected-day");
				if (dayLabel) {
					dayLabel.textContent = data.selectedDate.split("-").reverse().join("/");
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
							<div class="grid grid-cols-4 gap-4 p-4 items-center" id="resume-selected">
							</div>

						</div>

						<div class="p-2">
							<p class="font-bold tracking-wider">Totale</p>
							<p><span class="text-center p-3 text-xl font-bold" id="resume-total"></span>€</p>
						</div>

						<button
							class="mt-auto w-full p-3 bg-[var(--bg-med)] shadow-lg/20 text-[var(--text-primary)] font-bold rounded-xl hover:scale-102 cursor-pointer hover:shadow-xl/25 hover:bg-[var(--accent)] transition-all duration-150 ease-in-out">
							Vai al pagamento
						</button>
					</div>
				</div>
			`
			
		}
		
		_bindEvents(){
		
			
		}
		
	}
	
	customElements.define("reservation-resume-view", ReservationResumeView)