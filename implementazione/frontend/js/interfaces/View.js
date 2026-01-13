export default class View extends HTMLElement{

    constructor(config){
        super();
        this._config = config
    }
    display(data){ throw new Error("display() non implementato")}
    template(){ throw new Error("template() non implementato")}
    _bindEvents(){ throw new Error("_bindEvents() non implementato")}
}