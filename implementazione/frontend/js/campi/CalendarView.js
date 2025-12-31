import View from "../interfaces/View.js"
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

export default class CalendarView extends View {

	constructor(){
		super()
	}

	connectedCallback(){
		this.style.display = "contents";
		this.innerHTML = this.template()

		eventBus.notify(Events.CALENDAR_LOAD_EVENT)
	}

    display(data){ 
		if (!data.date || !data.slot) return 

        this.querySelector("#date-picker").appendChild(data.date);
        this.querySelector("#slot-picker").appendChild(data.slot);
	}

	template(){
		return `
			<div class="flex flex-col w-full h-full p-6 gap-6">
				<div class="h-full w-full flex gap-4">

					<div class="w-56 flex flex-col bg-[var(--bg-med)] rounded-xl" id="date-picker"></div>
					<div class="flex-1 bg-[var(--bg-med)] rounded-xl" id="slot-picker"></div>
					<div class="flex-1 bg-blue-300 rounded-xl"></div>
	
				</div>
			</div>`
	}


    _bindEvents(){ throw new Error("_bindEvents() non implementato")}
}

customElements.define("calendar-view", CalendarView)