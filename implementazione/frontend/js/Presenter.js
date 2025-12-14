export default class Presenter {
    _view
    constructor(view) {
        if (!(view.display && typeof(view.display) === "function"))  throw "View passata al presenter non implementa display()";
        if (!(view.template && typeof(view.template) === "function"))  throw "View passata al presenter non implementa template()";
        if (!(view.registerEvents && typeof(view.registerEvents) === "function"))  throw "View passata al presenter non implementa registerEvents()";
        this._view = view;
    }

    update() { throw "Il presenter non implementa il metodo update()"}
    _registerEvents() { throw "Il presenter non implementa il metodo registerEvents()"}
}
