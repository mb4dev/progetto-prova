import View from "./View.js"
import APIService from "./APIService.js";

export default class Presenter {
    _view

    constructor(view, config) {
        if (!view) throw new Error("view non può essere null");
        if (!(view instanceof View)) throw new Error("view deve implementare l'interfaccia View")

        this._view = view;
        this._config = config

        this.init();
    }

    init(){
        this._handleViewEvents()
    }

    update() { throw new Error("Il presenter non implementa il metodo update()") }
    _handleViewEvents() { throw new Error("Il presenter non implementa il metodo _handleViewEvents()") }
}
