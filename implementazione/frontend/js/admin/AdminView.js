import View from "../interfaces/View.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class AdminView extends View {
    #fieldsContainer
    #sportsContainer
    #activeTab = 'fields'
    #fields = []
    #sports = []

    constructor() {
        super();
    }

    connectedCallback() {
        this.id = "admin-view";
        this.style.display = "contents";
        this.innerHTML = this.template();

        this.#fieldsContainer = this.querySelector("#fields-container");
        this.#sportsContainer = this.querySelector("#sports-container");

        this._bindEvents();
    }

    display(data) {
        if (data.fields) {
            this.#fields = data.fields;
            this.renderFields();
        }
        if (data.sports) {
            this.#sports = data.sports;
            this.renderSports();
        }
    }

    renderFields() {
        const listHtml = this.#fields.length > 0 ? `
            <div class="space-y-3">
                ${this.#fields.map(field => `
                    <div class="bg-[var(--bg-light)] rounded-xl p-4 border border-white/5 flex items-center justify-between group hover:border-[var(--accent)]/30 transition-all">
                        <div class="flex-1">
                            <h4 class="font-bold text-[var(--text-primary)]">${field.name}</h4>
                            <p class="text-[var(--text-primary)] opacity-60 text-sm">${field.type} • €${field.price}/ora</p>
                        </div>
                        <button class="delete-field-btn p-2 hover:bg-red-500/10 rounded-lg transition-colors opacity-0 group-hover:opacity-100" 
                                data-id="${field.id}"
                                title="Elimina">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                `).join('')}
            </div>
        ` : `
            <div class="text-center py-8 text-[var(--text-primary)] opacity-50">
                Nessun campo disponibile
            </div>
        `;

        this.#fieldsContainer.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form creazione -->
                <div class="bg-[var(--bg-light)] rounded-2xl p-6 border border-white/5 h-fit">
                    <h3 class="text-xl font-bold text-[var(--text-primary)] mb-4">Crea Nuovo Campo</h3>
                    <form id="field-form" class="space-y-4">
                        <div>
                            <label class="block text-[var(--text-primary)] opacity-70 text-sm mb-2">Nome Campo</label>
                            <input type="text" 
                                   name="name" 
                                   required
                                   placeholder="Es: Campo A"
                                   class="w-full bg-[var(--bg-med)] text-[var(--text-primary)] rounded-xl px-4 py-3 border border-white/5 focus:border-[var(--accent)] focus:outline-none transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-[var(--text-primary)] opacity-70 text-sm mb-2">Tipo</label>
                            <input type="text" 
                                   name="type" 
                                   required
                                   placeholder="Es: Calcetto, Tennis"
                                   class="w-full bg-[var(--bg-med)] text-[var(--text-primary)] rounded-xl px-4 py-3 border border-white/5 focus:border-[var(--accent)] focus:outline-none transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-[var(--text-primary)] opacity-70 text-sm mb-2">Prezzo (€/ora)</label>
                            <input type="number" 
                                   name="price" 
                                   required
                                   min="0"
                                   step="0.01"
                                   placeholder="30.00"
                                   class="w-full bg-[var(--bg-med)] text-[var(--text-primary)] rounded-xl px-4 py-3 border border-white/5 focus:border-[var(--accent)] focus:outline-none transition-colors">
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-[var(--accent)] text-white font-semibold py-3 rounded-xl hover:opacity-90 transition-opacity">
                            + Aggiungi Campo
                        </button>
                    </form>
                </div>

                <!-- Lista campi -->
                <div class="bg-[var(--bg-light)] rounded-2xl p-6 border border-white/5">
                    <h3 class="text-xl font-bold text-[var(--text-primary)] mb-4">Campi Esistenti (${this.#fields.length})</h3>
                    <div class="max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                        ${listHtml}
                    </div>
                </div>
            </div>
        `;

        this._bindFieldEvents();
    }

    renderSports() {
        const listHtml = this.#sports.length > 0 ? `
            <div class="space-y-3">
                ${this.#sports.map(sport => `
                    <div class="bg-[var(--bg-light)] rounded-xl p-4 border border-white/5 flex items-center justify-between group hover:border-[var(--accent)]/30 transition-all">
                        <div class="flex items-center gap-4 flex-1">
                            ${sport.img ? `
                                <img src="${sport.img}" alt="${sport.name}" class="w-12 h-12 rounded-lg object-cover">
                            ` : `
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-[var(--accent)]/20 to-[var(--accent)]/5 flex items-center justify-center text-2xl">
                                    🏃
                                </div>
                            `}
                            <div class="flex-1">
                                <h4 class="font-bold text-[var(--text-primary)]">${sport.name}</h4>
                                <p class="text-[var(--text-primary)] opacity-60 text-sm">
                                    ${sport.description || 'Nessuna descrizione'} • €${sport.price}
                                </p>
                            </div>
                        </div>
                        <button class="delete-sport-btn p-2 hover:bg-red-500/10 rounded-lg transition-colors opacity-0 group-hover:opacity-100" 
                                data-id="${sport.id}"
                                title="Elimina">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                `).join('')}
            </div>
        ` : `
            <div class="text-center py-8 text-[var(--text-primary)] opacity-50">
                Nessuno sport disponibile
            </div>
        `;

        this.#sportsContainer.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form creazione -->
                <div class="bg-[var(--bg-light)] rounded-2xl p-6 border border-white/5 h-fit">
                    <h3 class="text-xl font-bold text-[var(--text-primary)] mb-4">Crea Nuovo Sport</h3>
                    <form id="sport-form" class="space-y-4">
                        <div>
                            <label class="block text-[var(--text-primary)] opacity-70 text-sm mb-2">Nome Sport</label>
                            <input type="text" 
                                   name="name" 
                                   required
                                   placeholder="Es: Calcio a 5"
                                   class="w-full bg-[var(--bg-med)] text-[var(--text-primary)] rounded-xl px-4 py-3 border border-white/5 focus:border-[var(--accent)] focus:outline-none transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-[var(--text-primary)] opacity-70 text-sm mb-2">Descrizione</label>
                            <textarea name="description" 
                                      rows="3"
                                      placeholder="Descrizione opzionale dello sport"
                                      class="w-full bg-[var(--bg-med)] text-[var(--text-primary)] rounded-xl px-4 py-3 border border-white/5 focus:border-[var(--accent)] focus:outline-none transition-colors resize-none"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-[var(--text-primary)] opacity-70 text-sm mb-2">URL Immagine</label>
                            <input type="url" 
                                   name="img" 
                                   placeholder="https://esempio.com/immagine.jpg"
                                   class="w-full bg-[var(--bg-med)] text-[var(--text-primary)] rounded-xl px-4 py-3 border border-white/5 focus:border-[var(--accent)] focus:outline-none transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-[var(--text-primary)] opacity-70 text-sm mb-2">Prezzo (€)</label>
                            <input type="number" 
                                   name="price" 
                                   required
                                   min="0"
                                   step="0.01"
                                   placeholder="15.00"
                                   class="w-full bg-[var(--bg-med)] text-[var(--text-primary)] rounded-xl px-4 py-3 border border-white/5 focus:border-[var(--accent)] focus:outline-none transition-colors">
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-[var(--accent)] text-white font-semibold py-3 rounded-xl hover:opacity-90 transition-opacity">
                            + Aggiungi Sport
                        </button>
                    </form>
                </div>

                <!-- Lista sport -->
                <div class="bg-[var(--bg-light)] rounded-2xl p-6 border border-white/5">
                    <h3 class="text-xl font-bold text-[var(--text-primary)] mb-4">Sport Esistenti (${this.#sports.length})</h3>
                    <div class="max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                        ${listHtml}
                    </div>
                </div>
            </div>
        `;

        this._bindSportEvents();
    }

    template() {
        return `
            <div class="w-full h-full p-6 flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <h2 class="text-3xl font-bold text-[var(--text-primary)]">Admin</h2>
                    <p class="text-[var(--text-primary)] opacity-70">Gestisci campi sportivi e sport</p>
                </div>

                <div class="bg-[var(--bg-med)] rounded-3xl p-6 border border-white/5 flex-1 flex flex-col">
                    <div class="flex gap-4 mb-6 border-b border-white/10">
                        <button class="tab-btn pb-3 px-4 font-bold text-[var(--accent)] border-b-2 border-[var(--accent)] transition-colors" data-tab="fields">
                            Campi
                        </button>
                        <button class="tab-btn pb-3 px-4 font-bold text-[var(--text-primary)] opacity-50 hover:opacity-100 transition-all" data-tab="sports">
                            Sport
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <div id="fields-container"></div>
                        <div id="sports-container" class="hidden"></div>
                    </div>
                </div>
            </div>
        `;
    }

    _bindEvents() {
        const tabBtns = this.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this._switchTab(e.target.dataset.tab);
            });
        });
    }

    _bindFieldEvents() {
        const form = this.querySelector('#field-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = {
                    id: Date.now().toString(), // Genera un ID temporaneo
                    name: formData.get('name'),
                    type: formData.get('type'),
                    price: parseFloat(formData.get('price'))
                };
                
                eventBus.notify(Events.ADMIN_ADD_FIELD, data);
                form.reset();
            });
        }

        const deleteBtns = this.querySelectorAll('.delete-field-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                if (confirm('Sei sicuro di voler eliminare questo campo?')) {
                    eventBus.notify(Events.ADMIN_DELETE_FIELD, { id });
                }
            });
        });
    }

    _bindSportEvents() {
        const form = this.querySelector('#sport-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = {
                    id: Date.now().toString(), // Genera un ID temporaneo
                    name: formData.get('name'),
                    description: formData.get('description') || '',
                    img: formData.get('img') || '',
                    price: parseFloat(formData.get('price'))
                };
                
                eventBus.notify(Events.ADMIN_ADD_SPORT, data);
                form.reset();
            });
        }

        const deleteBtns = this.querySelectorAll('.delete-sport-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                if (confirm('Sei sicuro di voler eliminare questo sport?')) {
                    eventBus.notify(Events.ADMIN_DELETE_SPORT, { id });
                }
            });
        });
    }

    _switchTab(tab) {
        const tabBtns = this.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            if (btn.dataset.tab === tab) {
                btn.classList.add('text-[var(--accent)]', 'border-b-2', 'border-[var(--accent)]');
                btn.classList.remove('opacity-50');
            } else {
                btn.classList.remove('text-[var(--accent)]', 'border-b-2', 'border-[var(--accent)]');
                btn.classList.add('opacity-50');
            }
        });

        if (tab === 'fields') {
            this.#fieldsContainer.classList.remove('hidden');
            this.#sportsContainer.classList.add('hidden');
        } else {
            this.#fieldsContainer.classList.add('hidden');
            this.#sportsContainer.classList.remove('hidden');
        }

        this.#activeTab = tab;
    }
}

customElements.define("admin-view", AdminView);