import View from "../interfaces/View.js"

export default class CorsiView extends View {
    constructor(){
        super();
    }

    connectedCallback(){
        this.id = "corsi-view";
        this.innerHTML = this.template();
        this._bindEvents();
    }

    display(data){
        // To be implemented
    }

    template(){
        return `<div>Corsi View</div>`;
    }

    _bindEvents(){
        // To be implemented
    }
}

customElements.define("corsi-view", CorsiView);
