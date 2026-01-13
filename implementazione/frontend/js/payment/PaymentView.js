import View from "../interfaces/View.js"
import Events from "../utility/Events.js";
import { eventBus } from "../utility/DefaultObserver.js";

export default class PaymentView extends View {

    #confirmBtn;
    #cancelBtn;

    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "payment-view";
        this.style.display = "contents";
        this.innerHTML = this.template();
        this._bindEvents();
    }

    display(data){
        const riepilogoSection = this.querySelector("#riepilogo-pagamento");
        const paymentTotalEl = this.querySelector("#payment-total");

        if (!riepilogoSection || !paymentTotalEl) return;

        if (!data) {
            riepilogoSection.innerHTML = `
                <h2 class="text-xl font-bold mb-4">Riepilogo Prenotazioni</h2>
                <div class="p-8 text-center opacity-50 italic text-sm">Il carrello è vuoto</div>
            `;
            paymentTotalEl.textContent = "0.00";
            return;
        }

        if (data.cartItems && data.cartItems.length > 0) {
            riepilogoSection.innerHTML = `
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2 pe-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--accent)]">
                        <circle cx="8" cy="21" r="1"/>
                        <circle cx="19" cy="21" r="1"/>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                    Riepilogo Prenotazioni
                </h2>
                <div class="flex flex-col gap-3 max-h-[500px] overflow-y-auto  pe-2 custom-scrollbar">
                    ${data.cartItems.map((item, index) => `
                        <div class="flex flex-col bg-[var(--bg-card)] p-3 border-1 border-[var(--accent)] rounded-xl shadow-xl/20">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-sm">${item.sport.title}</p>
                                    <p class="text-xs opacity-70">${item.date.split("-").reverse().join("/")}</p>
                                </div>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-1">
                                ${item.slots.map(s => `<span class="bg-[var(--bg-med)] px-2 py-1 rounded text-[10px]">${s}</span>`).join("")}
                            </div>
                            <div class="mt-2 text-right font-bold text-[var(--accent)]">
                                ${item.price.toFixed(2)}€
                            </div>
                        </div>
                    `).join("")}
                </div>
            `;
        } else {
            riepilogoSection.innerHTML = `
                <h2 class="text-xl font-bold mb-4">Riepilogo Prenotazioni</h2>
                <div class="p-8 text-center opacity-50 italic text-sm">Il carrello è vuoto</div>
            `;
        }

        paymentTotalEl.textContent = (data.cartTotal || 0).toFixed(2);
    }

    template(){
        return `
        	<div class="h-full w-full flex gap-4 p-6">
                <div class="w-80 flex flex-col rounded-xl" id="riepilogo-pagamento">

                </div>
                
                <div class="m-1 mt-6 mb-6 flex border-1"></div>

                <div class="flex-1 rounded-xl" id="dati-pagamento">
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-col gap-1">
                            <h1 class="text-2xl font-bold">Riepilogo pagamento</h1>
                            <p class="text-sm text-gray-500">Controlla i dettagli prima di confermare</p>
                        </div>

                    <div class="flex flex-col gap-4">
                        <div class="mb-6">
                            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--accent)]">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Dati Carta di Credito
                            </h2>
                        <div class="bg-[var(--bg-card)] p-4 rounded-xl">
                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" for="card-number">Numero Carta</label>
                                <input 
                                    type="text" 
                                    id="card-number" 
                                    name="cardNumber"
                                    placeholder="1234 5678 9012 3456"
                                    maxlength="19"
                                    class="w-full px-4 py-3 bg-[var(--bg-dark)] rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] font-mono"
                                    required
                                />
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" for="card-name">Intestatario</label>
                                <input 
                                    type="text" 
                                    id="card-name" 
                                    name="cardName"
                                    placeholder="NOME COGNOME"
                                    class="w-full px-4 py-3 bg-[var(--bg-dark)] rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] uppercase"
                                    required
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" for="card-expiry">Scadenza</label>
                                    <input 
                                        type="text" 
                                        id="card-expiry" 
                                        name="cardExpiry"
                                        placeholder="MM/AA"
                                        maxlength="5"
                                        class="w-full px-4 py-3 bg-[var(--bg-dark)] rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] font-mono"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" for="card-cvv">CVV</label>
                                    <input 
                                        type="text" 
                                        id="card-cvv" 
                                        name="cardCvv"
                                        placeholder="123"
                                        maxlength="3"
                                        class="w-full px-4 py-3 bg-[var(--bg-dark)] rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] font-mono"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-[var(--separator)] pt-6 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold">Totale</span>
                        <span class="text-3xl font-bold text-[var(--accent)]">
                            <span id="payment-total">0.00</span>€
                        </span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button id="cancel-payment-btn" class="flex-1 py-3 px-6 bg-[var(--bg-dark)] text-[var(--text-primary)] rounded-xl font-bold hover:bg-opacity-80 transition-all">
                        Annulla
                    </button>
                    <button id="confirm-payment-btn" class="flex-1 py-3 px-6 bg-[var(--accent)] text-[var(--text-secondary)] rounded-xl font-bold hover:shadow-lg transition-all">
                        Conferma Pagamento
                    </button>
                </div>
            </div>
        </div>
    </div>`
    }


    _bindEvents(){

    }
}


customElements.define("payment-view", PaymentView);
