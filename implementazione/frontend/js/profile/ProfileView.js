import View from "../interfaces/View.js"
import Events from "../utility/Events.js";
import Routes from "../utility/Routes.js"
import { eventBus } from "../utility/DefaultObserver.js";

export default class ProfileView extends View {
    #profileData = null;
    #isEditing = false;

    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "profile-view";
        this.style.display = "contents";
        this.render();
    }

    display(data){
        if (data.profile) {
            this.#profileData = data.profile;
        }
        if (data.mode !== undefined) {
            this.#isEditing = data.mode === "edit";
        }
        this.render();
    }

    render() {
        this.innerHTML = this.template();
        this._bindEvents();
    }

    template(){
        if (!this.#profileData) return `<div class="p-10 text-center text-gray-400">Caricamento...</div>`;

        return `
            <div class="flex flex-col gap-8 w-96 ">
                <div class="flex flex-col gap-1">
                    <h1 class="text-2xl font-bold">Profilo</h1>
                    <p class="text-sm text-gray-500">Gestisci le tue informazioni personali</p>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2 text-sm">
                        <label class="text-gray-400 font-medium ml-1">NOME</label>
                        ${this.#isEditing ? 
                            `<input type="text" id="name-input" value="${this.#profileData.name}" class="bg-[var(--bg-light)] border border-gray-700/50 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--accent)]/50 transition-all">` : 
                            `<div class="p-3 bg-[var(--bg-light)] rounded-xl border border-transparent">${this.#profileData.name}</div>`
                        }
                    </div>

                    <div class="flex flex-col gap-2 text-sm">
                        <label class="text-gray-400 font-medium ml-1">EMAIL</label>
                        ${this.#isEditing ? 
                            `<input type="email" id="email-input" value="${this.#profileData.email}" class="bg-[var(--bg-light)] border border-gray-700/50 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--accent)]/50 transition-all">` : 
                            `<div class="p-3 bg-[var(--bg-light)] rounded-xl border border-transparent">${this.#profileData.email}</div>`
                        }
                    </div>

                    <div class="flex flex-col gap-2 text-sm">
                        <label class="text-gray-400 font-medium ml-1">RUOLO</label>
                        <div class="p-3 bg-[var(--bg-light)]/50 text-gray-500 rounded-xl italic">
                            ${this.#profileData.admin ? "Amministratore" : "Utente"}
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    ${!this.#isEditing ? 
                        `<button id="edit-btn" class="flex-1 bg-[var(--accent)] text-white font-bold py-3 rounded-xl hover:brightness-70 active:scale-95 transition-all shadow-lg shadow-[var(--accent)]/20">Modifica</button>` : 
                        `
                        <button id="cancel-btn" class="flex-1 bg-gray-800 text-gray-400 font-bold py-3 rounded-xl hover:bg-gray-700 active:scale-95 transition-all">Annulla</button>
                        <button id="save-btn" class="flex-1 bg-[var(--accent)] text-white font-bold py-3 rounded-xl hover:brightness-70 active:scale-95 transition-all shadow-lg shadow-[var(--accent)]/20">Salva</button>
                        `
                    }
                </div>
            </div>`;
    }

    _bindEvents(){
        const editBtn = this.querySelector("#edit-btn");
        if (editBtn) {
            editBtn.addEventListener("click", () => {
                this.#isEditing = true;
                this.render();
            });
        }

        const cancelBtn = this.querySelector("#cancel-btn");
        if (cancelBtn) {
            cancelBtn.addEventListener("click", () => {
                this.#isEditing = false;
                this.render();
            });
        }

        const saveBtn = this.querySelector("#save-btn");
        if (saveBtn) {
            saveBtn.addEventListener("click", () => {
                const name = this.querySelector("#name-input").value;
                const email = this.querySelector("#email-input").value;
                
                eventBus.notify(Events.PROFILE_UPDATE_EVENT, {
                    ...this.#profileData,
                    name,
                    email
                });
            });
        }
    }
}


customElements.define("profile-view", ProfileView);
