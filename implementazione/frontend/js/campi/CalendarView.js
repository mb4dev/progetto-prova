import View from "../interfaces/View.js"

export default class CalendarView extends View {


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
			<div class="p-4 border-b" style="border-color: var(--border-color);">
				<div class="flex items-center gap-2 mb-4">
					<svg class="w-5 h-5" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="16" y1="2" x2="16" y2="6"></line>
						<line x1="8" y1="2" x2="8" y2="6"></line>
						<line x1="3" y1="10" x2="21" y2="10"></line>
					</svg>
					<h2 class="font-semibold">Seleziona Data</h2>

					<!-- TODO AGGIUNGERE CAMBIO DATA-->
				</div>
			</div>`
	}

	#renderDateCard(giorno, numero){
		const selectedClass = ""

		return `<div class="relative w-4/5 rounded-xl flex bg-[var(--bg-med)] border-2 border-[var(--border-color)] p-3 shadow-lg/20 cursor-pointer group hover:shadow-xl/25 hover:brightness-120">
					<div class="h-full w-full">
						<p class="absolute text-sm font-semibold  uppercase tracking-wider opacity-60 group-hover:text-[var(--accent)]">${giorno}</p>
					</div>
					<h1 class="font-bold text-3xl opacity-90 tracking-wider group-hover:text-[var(--accent)]">${numero}</h1>
				</div>`
	}

	#renderSlot(ora){
		return `		
			<div class="aspect-square rounded-xl font-medium flex flex-col items-center justify-center opacity-50 bg-[var(--bg-med)] border-[var(--border-color)] shadow-lg/20]">
				<div class="text-lg">${ora}</div>
			</div>`
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


	#renderAllSlots(){
		const timeSlots = this.#generateTimeSlots()
		return `
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
				<svg class="w-5 h-5" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<circle cx="12" cy="12" r="10"></circle>
					<polyline points="12 6 12 12 16 14"></polyline>
				</svg>
				Orari disponibili per 2025-01-15
			</h2>

        <div class="grid grid-rows-7 grid-cols-4 gap-4 p-5">
			${timeSlots.map(s => {return this.#renderSlot(s)}).join("")}
		</div>

		`
	}
	template(){
		const week = this.#getWeekDays()
		return `
			<div class="flex flex-col w-full h-full p-6 gap-6">
				<div class="h-full w-full flex gap-4">
					<div class="w-56 flex flex-col" id="date-select">
						${this.#renderSidebarHeader()}
						
						<div class="flex-1 p-2">
							<div class="h-full grid grid-rows-7 gap-4 place-items-center" id="days-grid">
								${
								week.map(d => { return this.#renderDateCard(d.giorno, d.numero)}).join("")}			
							</div>
						</div>
					</div>

					<div class="flex flex-1 w-1/2">
						${this.#renderAllSlots()}
					</div>

					<div class="flex flex-1 w-1/2 bg-blue-300"></div>
				</div>
			</div>`

	}
    _bindEvents(){ throw new Error("_bindEvents() non implementato")}
}

customElements.define("calendar-view", CalendarView)