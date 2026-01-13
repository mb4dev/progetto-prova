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

        if (data.cart) {
            riepilogoSection.innerHTML = "";
            riepilogoSection.appendChild(data.cart);
        }

        paymentTotalEl.textContent = (data.cartTotal || 0).toFixed(2);
    }

    template(){
        return `
        	<div class="h-full w-full flex gap-4 p-6">
                <div class="w-80 bg-[var(--bg-dark)] rounded-xl inset-shadow-sm/30" id="riepilogo-pagamento">

                </div>
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
