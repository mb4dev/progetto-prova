    import View from "../interfaces/View.js"
    import { eventBus } from "../utility/DefaultObserver.js"
    import Events from "../utility/Events.js"
    import SportCard from "./SportCard.js"
    
    export default class SportSelectionView extends View {
            
        constructor(config){
            if(!config.title || !config.subtitle || !config.itemType) throw new Error("Configurazione minima SportSelectionView non presente");
            
            super(config);
        }
        
        connectedCallback(){
            this.id = "campi-view";
            this.className = "w-full h-full";
            this.innerHTML = this.template();
            this._bindEvents();    
        }
        
        display(data){
            if(!data.items){
                throw new Error("Data non contiente oggetti items");
            }
            
            const grid = this.querySelector('#sports-grid');
            if (!grid) return;
            
            if (data.items === 0) {
                grid.innerHTML = '<div class="col-span-full text-[var(--text-primary)] opacity-50">Nessuno sport disponibile.</div>';
                return;
            }
            
            data.items.forEach(item => {
                const card = new SportCard();
                card.setAttribute('data-id', item.id);
                card.setAttribute('data-title', item.name);
                card.setAttribute('data-image', item.img);
                card.setAttribute('data-price', item.price);
                card.setAttribute('data-type', this._config.itemType);
                
                if(item.schedule){
                    card.setAttribute('data-schedule', JSON.stringify(item.schedule));
                }

                grid.appendChild(card);
            });
        }
        
        
        template(){
            return `
                <div class="flex flex-col w-full h-full p-6 gap-6">
                        <div class="flex flex-col gap-2">
                            <h1 class="text-3xl font-bold text-[var(--text-primary)]">${this._config.title}</h1>
                            <p class="text-[var(--text-primary)] opacity-70">${this._config.subtitle}</p>
                        </div>
        
                <div class="flex-1 overflow-y-auto custom-scrollbar pb-6 p-4" id="grid-container">
                    <div id="sports-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 auto-rows-fr">
                    </div>
                </div>
            </div>`
        }
        
        _bindEvents(){
            eventBus.notify(Events.SPORTS_LOAD_EVENT, {itemType : this._config.itemType});
        }
        
    }
    customElements.define("sport-selection-view", SportSelectionView);
    