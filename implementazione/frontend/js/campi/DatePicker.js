import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import View from "../interfaces/View.js"

export default class DatePicker extends View {
	constructor(){
		super()
	}
	
	connectedCallback(){
		this.style.display = "contents";
		this.innerHTML = this.template()
		
		
		this._bindEvents();
	}
	
	display(data){
		if (!data.week) return 
		
		
		const displayedWeek = this.querySelector("#displayed-week");
		
		const start = data.week.at(0)
		const end = data.week.at(-1)
		
		displayedWeek.textContent = start.numero + "/" + start.mese + "-" + end.numero + "/" + end.mese
		
		const grid = this.querySelector("#days-grid")
		grid.innerHTML = data.week.map(d => this.#dateCardTemplate(d)).join("")
	}
	
	
	#dateCardTemplate(date){		
		const giorno = date.giorno
		const numero = date.numero 
		const fullDate = date.fullDate
		return `
		<div data-date=${fullDate} class="date-card relative w-4/5 rounded-xl flex bg-[var(--bg-med)] border-2 border-[var(--border-color)] p-3 shadow-lg/20 cursor-pointer transition-all hover:bg-[var(--bg-light)] hover:scale-105 hover:shadow-xl/25">
			<div class="h-full w-full">
				<p class="absolute text-sm font-semibold uppercase tracking-wider opacity-60">${giorno}</p>
			</div>
			<h1 class="font-bold text-3xl opacity-90 tracking-wider">${numero}</h1>
		</div>
	`
	}
	
	#sidebarTemplate(){
		return `
			<div class="flex flex-col items-center gap-2">
				<div class="w-full flex flex-row justify-center items-center gap-2">
					<svg class="w-6 h-6" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="16" y1="2" x2="16" y2="6"></line>
						<line x1="8" y1="2" x2="8" y2="6"></line>
						<line x1="3" y1="10" x2="21" y2="10"></line>
					</svg>
				
					<h2 class="w-full font-semibold ">Seleziona Data</h2>
				</div>
				<div class="w-full flex flex-row justify-center items-center gap-2">
					<span class="w-6 h-6 text-white hover:text-[var(--accent)]" id="date-decrement">
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
							<path d="M14 8L10 12L14 16"
							stroke="currentColor"
							stroke-width="2"
							stroke-linecap="round"
							stroke-linejoin="round"/>
						</svg>
					</span>
					<p class="text-center" id="displayed-week">..-..</p>
					
					<span class="w-6 h-6 text-white hover:text-[var(--accent)]" id="date-increment">
						
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
							<path d="M10 8L14 12L10 16"
							stroke="currentColor"
							stroke-width="2"
							stroke-linecap="round"
							stroke-linejoin="round"/>
						</svg>
					</span>
				</div>
			</div>
		`
	}
	
	
	template(){
		return `
			<div class="p-4" id="date-picker-sidebar">
				${this.#sidebarTemplate()}
			</div>
			<div class="flex-1 px-4 pb-4">
				<div class="h-full grid grid-rows-[repeat(7,1fr)] gap-2 place-items-center"  id="days-grid">
					...CARICAMENTO...		
				</div>
			</div>
		`
	}
	
	_bindEvents(){ 
		
		this.querySelector("#date-increment").addEventListener("click", e => {
			e.preventDefault()
			eventBus.notify(Events.DATE_INCREMENT_EVENT);
		})
		
		this.querySelector("#date-decrement").addEventListener("click", e => {
			e.preventDefault()
			eventBus.notify(Events.DATE_DECREMENT_EVENT);
		})
		
		this.addEventListener("click", e => {
			const date = e.target.closest(".date-card");
			if(!date) return 
			
			this.querySelectorAll(".date-card").forEach(card => {
				card.classList.remove("bg-[var(--accent)]", "text-[var(--text-secondary)]");
				card.classList.add("bg-[var(--bg-med)]");
			});
			
			date.classList.remove("bg-[var(--bg-med)]");
			date.classList.add("bg-[var(--accent)]", "text-[var(--text-secondary)]");
			
			eventBus.notify(Events.DATE_SELECTED_EVENT, { selectedDate: date.dataset.date})
		})
	}
}

customElements.define("date-picker-view", DatePicker)