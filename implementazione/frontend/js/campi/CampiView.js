import View from "../interfaces/View.js"
import { eventBus } from "../utility/DefaultObserver.js"
import Events from "../utility/Events.js"
import SportCard from "./SportCard.js"

export default class CampiView extends View {
    #fields;

    
    constructor(){
        super();
        this.#fields = [];
    }

    connectedCallback(){
        this.id = "campi-view";
        this.className = "w-full h-full";
        this.innerHTML = this.template();
        this._bindEvents();    
    }

    display(data){
        if(data.fields){
            this.#fields = data.fields;
        
            const grid = this.querySelector('#sports-grid');
            if (!grid) return;

            if (this.#fields.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-[var(--text-primary)] opacity-50">Nessuno sport disponibile.</div>';
                return;
            }

            this.#fields.forEach(field => {
                const card = new SportCard();
                card.setAttribute('data-id', field.id);
                card.setAttribute('data-sport', field.name);
                card.setAttribute('data-image', field.img);
                card.setAttribute('data-price', field.price);
                grid.appendChild(card);
            });
        }
    }

    template(){
        return `
           	<div class="flex flex-col w-full h-full p-6 gap-6">
					<div class="flex flex-col gap-2">
						<h1 class="text-3xl font-bold text-[var(--text-primary)]">Prenotazione campi sportivi</h1>
						<p class="text-[var(--text-primary)] opacity-70">Scegli lo sport che preferisci.</p>
					</div>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar pb-6">
                <div id="sports-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 auto-rows-fr">
                
                </div>
            </div>
        </div>`
    }

    _bindEvents(){
        eventBus.notify(Events.FIELDS_LOAD_EVENT);
    }
}

customElements.define("campi-view", CampiView);
