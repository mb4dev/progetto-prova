import View from "../interfaces/View.js"

export default class StoricoView extends View {
    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "storico-view";
        this.innerHTML = this.template();
        this._bindEvents();
    }

    display(data){
        // To be implemented
    }

    template(){
        return `<div>Storico View</div>`;
    }

    _bindEvents(){
        // To be implemented
    }
}

customElements.define("storico-view", StoricoView);
