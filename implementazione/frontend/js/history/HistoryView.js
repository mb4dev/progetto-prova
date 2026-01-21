import View from "../interfaces/View.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

export default class HistoryView extends View {
    constructor() {
        super();
    }

    connectedCallback() {
        this.id = "history-view";
        this.style.display = "contents";
        this.innerHTML = this.template();
        this._bindEvents();
    }


    display(data) {
        const list = this.querySelector("#history-list");
        if (!list) return;

        const items = data?.items || [];

        if (items.length === 0) {
            list.innerHTML = `<div class="p-8 text-center opacity-50 italic text-sm">Nessuna prenotazione o pagamento effettuato.</div>`;
            return;
        }

        list.innerHTML = items
            .map((item) => {
                const statusDisplay = item.status ? 
                    `<div class="text-xs opacity-60">${item.status}</div>` : "";

                const cancelButton = item.status === "cancellabile" ? 
                    `<button data-id=${item.id} class="cancel-btn ml-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 ">
                        <span class="text-xs font-bold">×</span>
                    </button>` : "";

                const slots = item.slot
                    .map(slot => {
                        return `<span class="text-center m-3">${slot}<span>`
                    }).join("");
                

                return `
                    <div class="flex justify-between items-center p-3 mb-2 rounded-xl bg-[var(--bg-dark)]">
                        <div class="flex flex-col">
                            <span class="font-bold text-sm">${item.title}</span>
                            <span class="text-xs opacity-70">Data ${item.date} - Orari${slots}</span>
                        </div>
                        <div class="text-right text-sm flex items-center gap-3">
                            <div class="font-bold text-[var(--accent)]">${item.amount.toFixed(2)}€</div>
                            ${statusDisplay}
                            ${cancelButton}
                        </div>
                    </div>
        `;
            })
            .join("");

        this.querySelectorAll(".cancel-btn")?.forEach(
            btn => {
                btn.addEventListener("click", (e) => {
                    eventBus.notify(Events.HISTORY_DELETE_EVENT, {
                        id: btn.dataset["id"]
                    })                    
                })
            }
        )
    }

    template() {
        return `
            <div class="flex flex-col w-full h-full p-6 gap-4">
                <div class="flex flex-col gap-1">
                    <h1 class="text-2xl font-bold">Storico attività</h1>
                    <p class="text-sm text-gray-500">Visualizza prenotazioni e pagamenti effettuati</p>
                </div>
                <div id="history-list" class="flex-1 overflow-y-auto custom-scrollbar mt-2">
                </div>
            </div>
        `;
    }

    _bindEvents() {
        eventBus.notify(Events.HISTORY_LOAD_EVENT);
    }
}

customElements.define("history-view", HistoryView);

