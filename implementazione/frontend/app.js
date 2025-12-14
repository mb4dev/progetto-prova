class MockBackend {
    #token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSm9obiBEb2UiLCJhZG1pbiI6ZmFsc2UsImlhdCI6MTUxNjIzOTAyMn0.ISViWxHK_jvGDtUR6q8cwU9HRXz522lKxg-jtM00sYM"
    login(email, password){
        return this.#token;
    }
}


class LoginView extends HTMLElement {
    #submitBtn

    constructor(){
        super();

    }

    connectedCallback(){
        this.id = "login-view";
        this.style.display = "contents";

        this.innerHTML = this.template()

        this.#submitBtn = this.querySelector("#login-submit")

        this.registerEvents();
    }


    display(){
    }

    template(){
        return `
            <div class="flex flex-col text-center bg-[var(--bg-med)] p-6 rounded-2xl shadow-2xl w-11/12 max-w-sm md:max-w-md mx-auto"> 
                <h1 class="text-3xl font-extrabold mb-4 text-[var(--text-color)]">Accedi</h1>
                <p class="mb-6 text-sm text-[var(--text-muted)]">Non hai un account? 
                    <a class="text-[var(--accent)] hover:underline cursor-pointer font-medium">Registrati</a>.
                </p>
                <div id="login-fields" class="flex flex-col items-center space-y-5 px-0">
                    <input 
                        type="email" 
                        id="login-email" 
                        placeholder="Email" 
                        required 
                        class="w-full p-3 bg-[var(--bg-dark)] border border-[var(--border-color)] text-[var(--text-primary)] shadow-inner rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] text-black placeholder-gray-500 transition duration-150 ease-in-out">

                    <input 
                        type="password" 
                        id="login-password" 
                        placeholder="Password" 
                        required 
                        class="w-full p-3 bg-[var(--bg-dark)] border border-[var(--border-color)] text-[var(--text-primary)] shadow-inner rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] text-black placeholder-gray-500 transition duration-150 ease-in-out">
                
                    <button id="login-submit" class="w-full mt-4 p-3 bg-[var(--bg-light)] text-[var(--text-primary)] font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out">
                        Login
                    </button>
                </div>
            </div>`
    
    }


    registerEvents(){
        this.#submitBtn.addEventListener("click", (event) => {

            event.preventDefault();

            const email = this.querySelector("#login-email").value;
            const password = this.querySelector("#login-password").value;
            const submitEvent = new CustomEvent("login-submit", {
                bubbles: true, 
                detail: {
                    email: email,
                    password: password
                }
            })
            this.dispatchEvent(submitEvent)
        })
    }
}

customElements.define("login-view", LoginView);

class Presenter {
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


class LoginPresenter extends Presenter{
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



document.addEventListener("DOMContentLoaded", () => {
    const app = document.getElementById("app");
    const loginView = new LoginView()
    app.appendChild(loginView)
    
    const presenter = new LoginPresenter(loginView);
})











