import View from "../interfaces/View.js"

export default class PaymentView extends View {
    
    constructor(){
        super();
    }
    
    connectedCallback(){
        this.className = "w-full h-full flex flex-col p-6 gap-6 relative";
        this.innerHTML = this.template();
        this._bindEvents();
    }
    
    display(data){
        // Ensure data is valid
        if(!data || !data.sport) return;

        const container = this.querySelector('#payment-details');
        if(container){
            container.innerHTML = `
                <div class="bg-[var(--bg-dark)] border border-[var(--bg-light)] rounded-2xl p-6 shadow-lg flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 aspect-video md:aspect-square rounded-xl overflow-hidden bg-gray-800 relative">
                         <img src="${data.sport.image || ''}" class="w-full h-full object-cover" alt="${data.sport.title}">
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-[var(--text-primary)] mb-2">${data.sport.title}</h2>
                            <p class="text-[var(--text-primary)] opacity-70 mb-4">Completa la tua prenotazione per questo sport.</p>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[var(--accent)] font-bold text-xl">${data.sport.price}${data.sport.unit}</span>
                            </div>
                            
                            <div class="mt-4 p-4 bg-[var(--bg-light)]/10 rounded-lg flex flex-col gap-2">
                                <p class="text-[var(--text-primary)]"><strong>Data:</strong> <span class="opacity-80">${data.date || 'N/A'}</span></p>
                                <p class="text-[var(--text-primary)]"><strong>Orari:</strong> <span class="opacity-80">${data.slots ? data.slots.join(', ') : 'N/A'}</span></p>
                            </div>
                        </div>

                         <div class="mt-6 flex flex-col gap-3">
                            <label class="text-[var(--text-primary)] text-sm">Metodo di Pagamento</label>
                            <div class="flex gap-4">
                                <button class="flex-1 py-3 px-4 rounded-xl border border-[var(--accent)] text-[var(--accent)] hover:bg-[var(--accent)] hover:text-white transition-all font-medium">Carta di Credito</button>
                                <button class="flex-1 py-3 px-4 rounded-xl border border-gray-600 text-gray-400 hover:border-gray-400 hover:text-gray-200 transition-all font-medium">PayPal</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    template(){
        return `
            <div class="flex flex-col gap-2 mb-4">
                 <h1 class="text-3xl font-bold text-[var(--text-primary)]">Riepilogo e Pagamento</h1>
                 <p class="text-[var(--text-primary)] opacity-70">Conferma la tua selezione e procedi al pagamento.</p>
            </div>

            <div id="payment-details" class="flex-1 overflow-y-auto">
                <!-- Details will be injected here -->
                <div class="animate-pulse flex space-x-4">
                    <div class="rounded-xl bg-gray-700 h-64 w-full"></div>
                </div>
            </div>

            <div class="mt-auto flex justify-end gap-4 pt-4 border-t border-[var(--bg-light)]">
                 <button id="btn-back" class="px-6 py-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--bg-light)] transition-all">Indietro</button>
                 <button id="btn-confirm" class="px-8 py-2 rounded-lg bg-[var(--accent)] text-white hover:opacity-90 transition-all shadow-lg font-bold">Conferma e Paga</button>
            </div>
        `
    }
    
    _bindEvents(){
        const btnBack = this.querySelector('#btn-back');
        if(btnBack){
            btnBack.addEventListener('click', () => {
                 // Dispatch event to go back? Or use history?
                 // For now, maybe just log or do nothing as flow back isn't fully defined without context
                 console.log("Back clicked");
            });
        }

        const btnConfirm = this.querySelector('#btn-confirm');
        if(btnConfirm){
            btnConfirm.addEventListener('click', () => {
                alert("Funzionalità di pagamento non ancora implementata!");
            });
        }
    }
}

customElements.define("payment-view", PaymentView);
