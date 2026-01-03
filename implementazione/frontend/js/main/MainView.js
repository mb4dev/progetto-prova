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

	#main
	#activeRoute = null;

    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "main-view";
        this.style.display = "contents";

        this.innerHTML = this.template()

		this.#main = this.querySelector("#main-content")
		this.#campiBtn = this.querySelector("#side-menu-campi")
		this.#corsiBtn = this.querySelector("#side-menu-corsi")
		this.#abbonamentoBtn = this.querySelector("#side-menu-abbonamento")
		this.#storicoBtn = this.querySelector("#side-menu-storico")
		this.#profileBtn = this.querySelector("#side-menu-profile")

        this._bindEvents();
    }


    display(data){
		if (data.view) {
			this.#main.innerHTML = ""
			this.#main.appendChild(data.view)
		}
		if (data.route) {
			this.#activeRoute = data.route;
			this._updateActiveTab();
		}
	}

    template(){
        return `
			<div class="w-full h-full flex p-6 gap-6 bg-[var(--bg-dark)]">
				<aside id="side-menu" class="bg-[var(--bg-med)] w-[20%] rounded-3xl p-6 flex flex-col gap-8 shadow-xl/30 border border-white/5">
					<div class="px-2 py-4">
						<h2 class="text-2xl font-black italic text-white">GestioneCUS</h2>
					</div>
					
					<nav class="flex flex-col gap-2">
						<button class="nav-btn flex items-center gap-3 w-full p-4 rounded-2xl font-bold transition-all duration-300 hover:bg-white/5 text-gray-400" id="side-menu-campi">
							<span class="w-6 h-6 text-[var(--accent)]">
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="2"/><line x1="12" y1="4" x2="12" y2="20" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/><path d="M2 9C2 9 4 9 4 12C4 15 2 15 2 15" stroke="currentColor" stroke-width="2"/><path d="M22 9C22 9 20 9 20 12C20 15 22 15 22 15" stroke="currentColor" stroke-width="2"/></svg>
							</span> Prenota campo
						</button>
						<button class="nav-btn flex items-center gap-3 w-full p-4 rounded-2xl font-bold transition-all duration-300 hover:bg-white/5 text-gray-400" id="side-menu-corsi">
							<span class="w-6 h-6 text-[var(--accent)]">
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="5" r="2" stroke="currentColor" stroke-width="2"/><path d="M12 7V13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 10L12 8L18 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 10V8M20 10V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9 18L12 13L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</span> Prenota corso
						</button>
						<button class="nav-btn flex items-center gap-3 w-full p-4 rounded-2xl font-bold transition-all duration-300 hover:bg-white/5 text-gray-400" id="side-menu-abbonamento">
							<span class="w-6 h-6 text-[var(--accent)]">
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="2"/><path d="M3 10H21" stroke="currentColor" stroke-width="2"/><path d="M7 14H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="17" cy="14" r="2" stroke="currentColor" stroke-width="2"/><path d="M17 11V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
							</span> Abbonamento
						</button>
						<button class="nav-btn flex items-center gap-3 w-full p-4 rounded-2xl font-bold transition-all duration-300 hover:bg-white/5 text-gray-400" id="side-menu-storico">
							<span class="w-6 h-6 text-[var(--accent)]">
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 3C8 3 4.5 5.5 3 9L2 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</span> Storico
						</button>
					</nav>

					<div id="side-menu-profile" class="nav-btn mt-auto px-2 py-4 border-t border-white/5 cursor-pointer group hover:bg-white/5 rounded-2xl transition-all duration-300">
						<div class="flex items-center gap-3">
							<div class="profile-avatar w-10 h-10 rounded-full bg-[var(--accent)]/20 flex justify-center items-center text-sm font-bold border border-[var(--accent)]/30 group-hover:border-[var(--accent)] transition-all">TMP</div>
							<div class="flex flex-col">
								<span class="profile-name text-sm font-bold">Temp</span>
							</div>
						</div>
					</div>
				</aside>

				<main id="main-content" class="flex justify-center items-center bg-[var(--bg-med)] flex-1 rounded-3xl  border border-white/5 shadow-xl/30"></main>
			</div>`
    }


    _bindEvents(){
		this.#bindRoute(this.#campiBtn, Routes.MAIN_CAMPI);
		this.#bindRoute(this.#corsiBtn, Routes.MAIN_CORSI);
		this.#bindRoute(this.#abbonamentoBtn, Routes.MAIN_ABBONAMENTO);
		this.#bindRoute(this.#storicoBtn, Routes.MAIN_STORICO);
		this.#bindRoute(this.#profileBtn, Routes.MAIN_PROFILE);
    }

	#bindRoute(btn, route){
		btn.addEventListener("click", (e) => {
			e.preventDefault();
			eventBus.notify(Events.MAIN_NAVIGATE, { main: route });
		});
	}

	_updateActiveTab(){
		const routesMap = [
			{ btn: this.#campiBtn, route: Routes.MAIN_CAMPI },
			{ btn: this.#corsiBtn, route: Routes.MAIN_CORSI },
			{ btn: this.#abbonamentoBtn, route: Routes.MAIN_ABBONAMENTO },
			{ btn: this.#storicoBtn, route: Routes.MAIN_STORICO },
			{ btn: this.#profileBtn, route: Routes.MAIN_PROFILE }
		];

		routesMap.forEach(({ btn, route }) => {
			if (route === this.#activeRoute) {
				btn.classList.add("bg-[var(--bg-light)]", "text-black", "shadow-xl");
				btn.classList.remove("text-gray-400", "hover:bg-white/5");
			} else {
				btn.classList.remove("bg-[var(--bg-light)]", "text-black", "shadow-xl");
				btn.classList.add("text-gray-400", "hover:bg-white/5");
			}
		});
	}
}

customElements.define("main-view", MainView);
