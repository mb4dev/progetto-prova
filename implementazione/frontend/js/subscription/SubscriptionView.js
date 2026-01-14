import View from "../interfaces/View.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

export default class SubscriptionView extends View {


    constructor() {
        super();
    }

    connectedCallback() {
        this.id = "subscription-view";
        this.style.display = "contents";
        this.innerHTML = this.template();
        this._bindEvents();
    }

    display(data) {

        if(data.cart){
            const container = this.querySelector("#carrello");
            container.innerHTML = "";
            container.appendChild(data.cart);
        }

        const list = this.querySelector("#subscription-list");
        if (!list) return;

        const items = data?.items || [];

        if (items.length === 0) {
            list.innerHTML = `<div class="p-8 text-center opacity-50 italic text-sm">Nessun abbonamento disponibile.</div>`;
            return;
        }

        list.innerHTML = items
            .map(
                (item) => `
            <div class="flex justify-between items-center p-3 mb-2 rounded-xl bg-[var(--bg-med)]">
                <div class="flex flex-col">
                    <span class="font-bold text-sm">${item.name}</span>
                    <span class="text-xs opacity-70">${item.description}</span>
                </div>
                <div class="text-right text-sm">
                    <div class="font-bold text-[var(--accent)]">${item.price.toFixed(2)}€</div>
                    <button class="mt-1 px-3 py-1 rounded-lg bg-[var(--accent)] text-xs font-bold text-[var(--text-secondary)]" data-id="${item.id}">
                        Acquista
                    </button>
                </div>
            </div>
        `
            )
            .join("");

        this.querySelectorAll("#subscription-list button[data-id]").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                const id = e.currentTarget.dataset.id;
                eventBus.notify(Events.SUBSCRIPTION_SELECTED_EVENT, { id });
            });
        });
    }

    template() {
        return `
         <div class="h-full w-full flex gap-4 p-6">

            <div class="flex flex-col w-full h-full p-6 gap-4">
                <div class="flex flex-col gap-1">
                    <h1 class="text-2xl font-bold">Abbonamenti</h1>
                    <p class="text-sm text-gray-500">Scegli il pacchetto più adatto alle tue esigenze</p>
                </div>
                <div id="subscription-list" class="flex-1 overflow-y-auto custom-scrollbar mt-2">
                </div>
            </div>
            <div class="w-80 bg-[var(--bg-dark)] rounded-xl inset-shadow-sm/30" id="carrello">   
            </div>
            </div>
        `;
    }

    _bindEvents() {
        eventBus.notify(Events.SUBSCRIPTION_LOAD_EVENT);
    }
}

customElements.define("subscription-view", SubscriptionView);

