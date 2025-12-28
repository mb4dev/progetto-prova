import View from "../view.js"

export default class DatePicker extends View {
	#selectedDay
	#isSelected
	
	constructor(){
		super()
	}
	
	connectedCallback(){
		this.style.display = "contents";
		this.innerHTML = this.template()
	}
	
	
	display(data){ throw new Error("display() non implementato")}
	
	#getWeekDays(){
		const options = {
			weekday: "long",			
			day: "2-digit"
		}
		const days = [];
		
		for(let i = 0; i < 7; i++){
			const day = new Date()
			day.setDate(day.getDate() + i)
			const date = day.toLocaleDateString("it-IT", options)
			const [d, n] = date.split(" ")
			
			days.push({giorno: d, numero:n, date: date})
		}
		return days;
	}
	
	#renderSidebarHeader(){
		return `
			<div class="p-4">
				<div class="flex items-center gap-2">
					<svg class="w-5 h-5" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="16" y1="2" x2="16" y2="6"></line>
						<line x1="8" y1="2" x2="8" y2="6"></line>
						<line x1="3" y1="10" x2="21" y2="10"></line>
					</svg>
					<h2 class="font-semibold">Seleziona Data</h2>
				</div>
			</div>
		`
	}
	
	#renderDateCard(giorno, numero){		
		return `
			<div class="relative w-4/5 rounded-xl flex bg-[var(--bg-med)] border-2 border-[var(--border-color)] p-3 shadow-lg/20 cursor-pointer group hover:shadow-xl/25 hover:brightness-120">
				<div class="h-full w-full">
					<p class="absolute text-sm font-semibold  uppercase tracking-wider opacity-60 group-hover:text-[var(--accent)]">${giorno}</p>
				</div>
				<h1 class="font-bold text-3xl opacity-90 tracking-wider group-hover:text-[var(--accent)]">${numero}</h1>
			</div>
		`
	}
	
	template(){
		const week = this.#getWeekDays()
		
		return `
			${this.#renderSidebarHeader()}
			<div class="flex-1 p-4">
				<div class="h-full grid grid-rows-7 gap-4 place-items-center" id="days-grid">
					${week.map(d => this.#renderDateCard(d.giorno, d.numero)).join("")}			
				</div>
			</div>
		`
	}

	_bindEvents(){ throw new Error("_bindEvents() non implementato")}
}

customElements.define("date-picker-view", DatePicker)