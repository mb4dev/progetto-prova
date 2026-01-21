import View from "../interfaces/View.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class AdminView extends View {
    #fieldsContainer
    #sportsContainer
    #addFieldBtn
    #addSportBtn
    #activeTab = 'fields'

    constructor() {
        super();
    }

    connectedCallback() {
        this.id = "admin-view";
        this.style.display = "contents";
        this.innerHTML = this.template();

        this.#fieldsContainer = this.querySelector("#fields-container");
        this.#sportsContainer = this.querySelector("#sports-container");
        this.#addFieldBtn = this.querySelector("#add-field-btn");
        this.#addSportBtn = this.querySelector("#add-sport-btn");

        this._bindEvents();
    }

    display(data) {
        if (data.fields) {
            this.renderFields(data.fields);
        }
        if (data.sports) {
            this.renderSports(data.sports);
        }
    }

    renderFields(fields) {
        this.#fieldsContainer.innerHTML = `
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Campi Sportivi</h3>
                    <button id="add-field-btn" class="bg-[var(--accent)] text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors">
                        Aggiungi Campo
                    </button>
                </div>
                <div class="grid gap-4">
                    ${fields.map(field => this.#renderFieldCard(field)).join('')}
                </div>
            </div>
        `;

        this.#addFieldBtn = this.querySelector("#add-field-btn");
        this._bindFieldEvents();
    }

    renderSports(sports) {
        this.#sportsContainer.innerHTML = `
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Sport</h3>
                    <button id="add-sport-btn" class="bg-[var(--accent)] text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors">
                        Aggiungi Sport
                    </button>
                </div>
                <div class="grid gap-4">
                    ${sports.map(sport => this.#renderSportCard(sport)).join('')}
                </div>
            </div>
        `;

        this.#addSportBtn = this.querySelector("#add-sport-btn");
        this._bindSportEvents();
    }

    #renderFieldCard(field) {
        return `
            <div class="bg-[var(--bg-light)] rounded-lg p-4 border border-white/10">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-white">${field.name}</h4>
                        <p class="text-gray-400">${field.type}</p>
                        <p class="text-[var(--accent)] font-semibold">€${field.price}/ora</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="edit-field-btn text-blue-400 hover:text-blue-300" data-id="${field.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button class="delete-field-btn text-red-400 hover:text-red-300" data-id="${field.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    #renderSportCard(sport) {
        return `
            <div class="bg-[var(--bg-light)] rounded-lg p-4 border border-white/10">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-white">${sport.name}</h4>
                        <p class="text-gray-400">${sport.description || ''}</p>
                        <p class="text-[var(--accent)] font-semibold">€${sport.price}</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="edit-sport-btn text-blue-400 hover:text-blue-300" data-id="${sport.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button class="delete-sport-btn text-red-400 hover:text-red-300" data-id="${sport.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    template() {
        return `
            <div class="w-full h-full p-6 bg-[var(--bg-dark)]">
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-white mb-2">Pannello Admin</h2>
                    <p class="text-gray-400">Gestisci campi sportivi e sport</p>
                </div>

                <div class="bg-[var(--bg-med)] rounded-3xl p-6 border border-white/5">
                    <div class="flex gap-4 mb-6 border-b border-white/10">
                        <button class="tab-btn pb-2 px-4 font-bold text-[var(--accent)] border-b-2 border-[var(--accent)]" data-tab="fields">
                            Campi
                        </button>
                        <button class="tab-btn pb-2 px-4 font-bold text-gray-400 hover:text-white transition-colors" data-tab="sports">
                            Sport
                        </button>
                    </div>

                    <div id="fields-container"></div>
                    <div id="sports-container" class="hidden"></div>
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
        if (this.#addFieldBtn) {
            this.#addFieldBtn.addEventListener('click', () => {
                this.dispatchEvent(new CustomEvent('admin_add_field', { detail: {} }));
            });
        }

        const editBtns = this.querySelectorAll('.edit-field-btn');
        editBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.closest('button').dataset.id;
                this.dispatchEvent(new CustomEvent('admin_edit_field', { detail: { id } }));
            });
        });

        const deleteBtns = this.querySelectorAll('.delete-field-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.closest('button').dataset.id;
                this.dispatchEvent(new CustomEvent('admin_delete_field', { detail: { id } }));
            });
        });
    }

    _bindSportEvents() {
        if (this.#addSportBtn) {
            this.#addSportBtn.addEventListener('click', () => {
                this.dispatchEvent(new CustomEvent('admin_add_sport', { detail: {} }));
            });
        }

        const editBtns = this.querySelectorAll('.edit-sport-btn');
        editBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.closest('button').dataset.id;
                this.dispatchEvent(new CustomEvent('admin_edit_sport', { detail: { id } }));
            });
        });

        const deleteBtns = this.querySelectorAll('.delete-sport-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.closest('button').dataset.id;
                this.dispatchEvent(new CustomEvent('admin_delete_sport', { detail: { id } }));
            });
        });
    }

    _switchTab(tab) {
        const tabBtns = this.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            if (btn.dataset.tab === tab) {
                btn.classList.add('text-[var(--accent)]', 'border-b-2', 'border-[var(--accent)]');
                btn.classList.remove('text-gray-400');
            } else {
                btn.classList.remove('text-[var(--accent)]', 'border-b-2', 'border-[var(--accent)]');
                btn.classList.add('text-gray-400');
            }
        });

        const containers = {
            'fields': this.#fieldsContainer,
            'sports': this.#sportsContainer
        };

        Object.values(containers).forEach(container => {
            if (container) container.classList.add('hidden');
        });

        if (containers[tab]) {
            containers[tab].classList.remove('hidden');
        }

        this.#activeTab = tab;
    }
}

customElements.define("admin-view", AdminView);