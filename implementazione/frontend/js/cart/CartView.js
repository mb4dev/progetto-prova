
import View from "../interfaces/View.js";
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class CartView extends View {

    #checkoutBtn;
    #container;

    constructor() {
        super();
    }

    connectedCallback() {
        this.style.display = "contents";
        this.innerHTML = this.template();
        this.#container = this.querySelector("#cart-container");
        this._bindEvents();
    }

    display(data) {

        const cartList = this.querySelector("#cart-list");
        const cartTotalEl = this.querySelector("#cart-total");

        if (data.cartItems && data.cartItems.length > 0) {
            cartList.innerHTML = data.cartItems.map((item, index) => `
                <div class="flex flex-col bg-[var(--bg-card)] p-3 m-2 border-1  border-[var(--accent)] rounded-xl shadow-xl/20">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-sm">${item.sport.title}</p>
                            <p class="text-xs opacity-70">${item.date.split("-").reverse().join("/")}</p>
                        </div>
                        <button data-index="${index}" class="remove-item-btn text-red-400 hover:text-red-600">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </button>
                    </div>
                    <div class="mt-2 flex flex-wrap gap-1">
                         ${item.slots.map(s => `<span class="bg-[var(--bg-med)] px-2 py-1 rounded text-[10px]">${s}</span>`).join("")}
                    </div>
                    <div class="mt-2 text-right font-bold text-[var(--accent)]">
                        ${item.price.toFixed(2)}€
                    </div>
                </div>
            `).join("");
            
            this.querySelectorAll(".remove-item-btn").forEach(btn => {
                btn.addEventListener("click", (e) => {
                    const idx = e.currentTarget.dataset.index;
                    eventBus.notify("cart:remove-item", { index: parseInt(idx) });
                });
            });

        } else {
            cartList.innerHTML = `<div class="p-8 text-center opacity-50 italic text-sm">Il carrello è vuoto</div>`;
        }

        if(cartTotalEl) cartTotalEl.textContent = (data.cartTotal || 0).toFixed(2);
    }

    template() {
        return `
            <div class="flex flex-col h-full px-2 py-4 rounded-xl" id="cart-container">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2 px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--accent)]"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    Carrello
                </h2>

                <div class="flex-1 overflow-y-auto min-h-0 mb-4 px-1" id="cart-list">
                    <!-- Cart Items -->
                </div>

                <div class="mt-auto bg-[var(--bg-med)] p-4 rounded-xl shadow-lg/10">
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-sm font-bold opacity-70">Totale</span>
                        <span class="text-2xl font-bold"><span id="cart-total">0.00</span>€</span>
                    </div>
                    <button id="checkout-btn" class="w-full bg-[var(--accent)] text-[var(--text-secondary)] py-3 rounded-xl font-bold hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                        Paga Ora
                    </button>
                    <button id="clear-cart-btn" class="w-full mt-2 py-2 text-xs opacity-50 hover:opacity-100 underline text-center">
                        Svuota carrello
                    </button>
                </div>
            </div>
        `;
    }

    _bindEvents() {
        this.querySelector("#checkout-btn").addEventListener("click", () => {
             eventBus.notify(Events.PAYMENT_PROCEED_EVENT);
        });
        
        this.querySelector("#clear-cart-btn").addEventListener("click", () => {
             eventBus.notify("cart:clear");
        });
    }
}

customElements.define("cart-view", CartView);
