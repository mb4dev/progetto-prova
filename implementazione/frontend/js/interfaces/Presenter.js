import View from "./View.js"
import APIService from "./APIService.js";
import { apiService } from "../utility/MockAPIService.js";

export default class Presenter {
    _view
    _service
    constructor(view) {
        if (!view) throw new Error("view non può essere null");
        if (!(view instanceof View)) throw new Error("view deve implementare l'interfaccia View")

        this._view = view;
        this._service = apiService;
    }

    init(){
        this._handleViewEvents()
    }

    update() { throw new Error("Il presenter non implementa il metodo update()") }
    _handleViewEvents() { throw new Error("Il presenter non implementa il metodo _handleViewEvents()") }
}
