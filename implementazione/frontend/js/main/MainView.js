import View from "../interfaces/View.js"
import Events from "../utility/Events.js";
import Routes from "../utility/Routes.js"
import { eventBus } from "../utility/DefaultObserver.js";

export default class MainView extends View {
	#profileBtn 
	#campiBtn
	#corsiBtn
	#abbonamentoBtn
	#storicoBtn

    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "main-view";
        this.style.display = "contents";

        this.innerHTML = this.template()

		this.#profileBtn = this.querySelector("#side-menu-profile")
		this.#campiBtn = this.querySelector("#side-menu-campi")
		this.#corsiBtn = this.querySelector("#side-menu-corsi")
		this.#abbonamentoBtn = this.querySelector("#side-menu-abbonamento")
		this.#storicoBtn = this.querySelector("#side-menu-storico")

        this._bindEvents();
    }


    display(data){

	}

    template(){
        return `
			<div class="w-full h-full flex p-5 gap-5">
				<aside id="side-menu" class="bg-[var(--bg-med)] w-[15%] rounded-lg p-2 text-center flex flex-col justify-center gap-5">
					<button class="bg-[var(--bg-light)] p-2 cursor-pointer font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out shadow-2xl" id="side-menu-profile">Visualizza profilo</button>
					<button class="bg-[var(--bg-light)] p-2 cursor-pointer font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out shadow-2xl" id="side-menu-campi">Prenota campo</button>
					<button class="bg-[var(--bg-light)] p-2 cursor-pointer font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out shadow-2xl" id="side-menu-corsi">Prenota corso</button>
					<button class="bg-[var(--bg-light)] p-2 cursor-pointer font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out shadow-2xl" id="side-menu-abbonamento">Compra abbonamento</button>
					<button class="bg-[var(--bg-light)] p-2 cursor-pointer font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out shadow-2xl" id="side-menu-storico">Storico prenotazioni</button>
				</aside>

				<main id="main-content" class="bg-[var(--bg-med)] w-[85%] rounded-lg"></main>
			</div>`
    }


    _bindEvents(){
		this.#profileBtn.addEventListener("click", (e) => {
			e.preventDefault()
			eventBus.notify(Events.MAIN_SELECT_EVENT, {
				main: Routes.MAIN_PROFILE 
			});
		})

		this.#campiBtn.addEventListener("click", (e) => {
			e.preventDefault()
			eventBus.notify(Events.MAIN_SELECT_EVENT, {
				main: Routes.MAIN_CAMPI 
			});
		})

		this.#corsiBtn.addEventListener("click", (e) => {
			e.preventDefault()
			eventBus.notify(Events.MAIN_SELECT_EVENT, {
				main: Routes.MAIN_CORSI 
			});
		})

		this.#abbonamentoBtn.addEventListener("click", (e) => {
			e.preventDefault()
			eventBus.notify(Events.MAIN_SELECT_EVENT, {
				main: Routes.MAIN_ABBONAMENTO 
			});
		})

		this.#storicoBtn.addEventListener("click", (e) => {
			e.preventDefault()
			eventBus.notify(Events.MAIN_SELECT_EVENT, {
				main: Routes.MAIN_STORICO 
			});
		})
    }
}

customElements.define("main-view", MainView);
