import View from "../interfaces/View.js"

export default class AbbonamentoView extends View {
    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "abbonamento-view";
        this.innerHTML = this.template();
        this._bindEvents();
    }

    display(data){
        // To be implemented
    }

    template(){
        return `<div>Abbonamento View</div>`;
    }

    _bindEvents(){
        // To be implemented
    }
}

customElements.define("abbonamento-view", AbbonamentoView);
