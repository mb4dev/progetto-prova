import Presenter from "../Presenter.js"

export default class LoginPresenter extends Presenter{
    constructor(view) {
        super(view)

        this._registerEvents()
    }

    _registerEvents(){
        this._view.addEventListener("login-submit", (e) => {
            console.log(e.detail)
        })
    }
}
