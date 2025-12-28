import View from "../view.js"
import DatePicker from "./DatePicker.js";
import SlotPicker from "./SlotPicker.js";
import SlotPickerPresenter from "./SlotPickerPresenter.js";

export default class CalendarView extends View {

	constructor(){
		super()
	}

	connectedCallback(){
		this.style.display = "contents";
		this.innerHTML = this.template()

		const datePicker = new DatePicker();
		const slotPicker = new SlotPicker();

		this.querySelector("#date-picker").appendChild(datePicker);
		this.querySelector("#slot-picker").appendChild(slotPicker);

		const presenter = new SlotPickerPresenter(slotPicker);
		presenter.init();

	}


    display(data){ throw new Error("display() non implementato")}

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