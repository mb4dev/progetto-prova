	import View from "../interfaces/View.js"
	
	import Events from "../utility/Events.js"
	import { eventBus } from "../utility/DefaultObserver.js"
	export default class SlotPicker extends View {
		
		constructor(){
			super()
		}
		
		connectedCallback(){
			this.style.display = "contents";
			this.innerHTML = this.template()
			this._bindEvents();
		}
		
		
		display(data) {
			if (!data.selected) return;
			
			const dateLabel = this.querySelector("#selected-date");
			if (dateLabel && data.selectedDate) {
				dateLabel.textContent = "Orari disponibili per " + data.selectedDate.split("-").reverse().join("-");
			}
			
			const grid = this.querySelector("#slot-grid");
			if (!grid) return;
			
			const allSlots = this.#generateTimeSlots();
			const occupied = data.occupied || [];
			const selected = data.selected;
			
			grid.innerHTML = allSlots.map(time => {
				const isOccupied = occupied.includes(time);
				const isSelected = selected.has(time);
				
				return this.#renderSlot(time, isOccupied, isSelected);
			}).join("");
		}
		
		#renderSlot(ora, occupato, selezionato) {

			var bgClass = "bg-[var(--bg-med)]" ;
			var cursorClass = "cursor-pointer"	
			var shadowClass = "shadow-xl/20"
			if (occupato) {
				bgClass = "opacity-40"; 
				cursorClass = "cursor-not-allowed"
				shadowClass = ""
			} else if (selezionato) {
				bgClass = "bg-[var(--accent)]";
			var shadowClass = "shadow-xl/30"

			}
			
			return `        
        <div 
            data-time="${ora}" 
            data-occupato="${occupato}"
            class="time-slot group relative p-3 font-medium flex flex-col items-center justify-center gap-1 text-white ${cursorClass} ${bgClass} ${shadowClass} border-[var(--bg-light)] rounded-lg border-2 hover:bg-[var(--bg-light)] hover:scale-105  transition-all ease-in-out ">
            <div class="text-base font-semibold">${ora}</div>
            <div class="text-xs ${occupato ? 'opacity-80' : 'opacity-0 pointer-events-none'}">
                ${occupato ? 'Occupato' : ''}
            </div>
            ${!occupato ? '<div class="absolute inset-0 opacity-0 rounded-lg"></div>' : ''}
        </div>`;
		}
				
		template(){
			return `
			<div class="flex flex-col h-full overflow-hidden">
				<div class="p-4 pb-3 flex justify-center items-center">
					<h2 class="text-lg font-semibold flex items-center gap-2">
						<svg class="w-5 h-5 flex-shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<circle cx="12" cy="12" r="10"></circle>
							<polyline points="12 6 12 12 16 14"></polyline>
						</svg>
						<span id="selected-date">Seleziona un giorno</span>
					</h2>
				</div>
				
				<div class="flex-1 px-4 pb-4 overflow-y-auto">
					<div id="slot-grid" class="grid grid-cols-4 gap-3 auto-rows-fr">
					</div>
				</div>
			</div>
			`
		}
		
		_bindEvents(){
			
			this.addEventListener("click", e => {
				
				const slot = e.target.closest(".time-slot");
				if(!slot || slot.dataset.occupato === "true") return 
				
				eventBus.notify(Events.SLOT_SELECTED_EVENT, { time: slot.dataset.time})
				
			});
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
	}
	
	customElements.define("slot-picker-view", SlotPicker)