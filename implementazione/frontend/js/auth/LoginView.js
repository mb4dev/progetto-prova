import View from "../interfaces/View.js"
import Events from "../utility/Events.js";
import Routes from "../utility/Routes.js"

export default class LoginView extends View {
    #submitBtn
    #registerLink

    constructor(){
        super();

    }

    connectedCallback(){
        this.id = "login-view";
        this.style.display = "contents";

        this.innerHTML = this.template()

        this.#submitBtn = this.querySelector("#auth-submit")
        this.#registerLink = this.querySelector("#register-link")

        this._bindEvents();
    }


    display(){}

    template(){
        return `
            <div class="flex flex-col text-center bg-[var(--bg-med)] p-6 rounded-2xl shadow-2xl w-11/12 max-w-sm md:max-w-md mx-auto"> 
                <h1 class="text-3xl font-extrabold mb-4 text-[var(--text-color)]">Accedi</h1>
                <p class="mb-6 text-sm text-[var(--text-muted)]">Non hai un account? 
                    <a id="register-link" class="text-[var(--accent)] hover:underline cursor-pointer font-medium">Registrati</a>.
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
                
                    <button id="auth-submit" class="w-full mt-4 p-3 bg-[var(--bg-light)] text-[var(--text-primary)] font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out">
                        Login
                    </button>
                </div>
            </div>`
    
    }


    _bindEvents(){
        this.#bindSubmit()
        this.#bindRouting()
    }

    #bindSubmit(){
        this.#submitBtn.addEventListener("click", (event) => {
            event.preventDefault();

            const email = this.querySelector("#login-email").value;
            const password = this.querySelector("#login-password").value;
            const submitEvent = new CustomEvent(Events.AUTH_SUBMIT_EVENT, {
                bubbles: true, 
                detail: {
                    email: email,
                    password: password
                }
            })
            this.dispatchEvent(submitEvent)
        })
    }

    #bindRouting(){
        this.#registerLink.addEventListener("click", (e) => {
            const event = new CustomEvent(Events.ROUTING_EVENT, {
                bubbles: true,
                detail: {
                    route: Routes.REGISTER
                }
            })

            this.dispatchEvent(event)
        })
    }

}

customElements.define("login-view", LoginView);
