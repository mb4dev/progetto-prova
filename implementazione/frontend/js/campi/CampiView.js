import View from "../interfaces/View.js"

export default class CampiView extends View {
    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "campi-view";
        this.innerHTML = this.template();
        this._bindEvents();
    }

    display(data){
        // To be implemented
    }

    template(){
        return `<div>Campi View</div>`;
    }

    _bindEvents(){
        // To be implemented
    }
}

customElements.define("campi-view", CampiView);
