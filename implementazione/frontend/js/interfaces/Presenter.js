import View from "./View.js"
import APIService from "./APIService.js";

export default class Presenter {
    _view
    _service
    constructor(view, service) {
        if (!view) throw new Error("view non può essere null");
        if (!(view instanceof View)) throw new Error("view deve implementare l'interfaccia View")

        if (!service) throw new Error("service non può essere null");
        if (!(service instanceof APIService)) throw new Error("service deve essere un APIService");
        this._view = view;
        this._service = service;
    }

    init(){
        this._handleViewEvents()
    }

    update() { throw new Error("Il presenter non implementa il metodo update()") }
    _handleViewEvents() { throw new Error("Il presenter non implementa il metodo _handleViewEvents()") }
}
