import View from "./View.js"

export default class Presenter {
    _view
    constructor(view) {
        if (!view) throw new Error("view non può essere null");
        if (!(view instanceof View)) throw new Error("view deve implementare l'interfaccia View")

        this._view = view;
    }

    init(){
        this._handleViewEvents()
    }

    update() { throw new Error("Il presenter non implementa il metodo update()") }
    _handleViewEvents() { throw new Error("Il presenter non implementa il metodo _handleViewEvents()") }
}
