import View from "../View.js"
import Events from "../../Events.js";


export default class RegisterView extends View {
	#submitBtn
	#loginLink

	constructor(){
		super();

	}

	connectedCallback(){
		this.id = "login-view";
		this.style.display = "contents";

		this.innerHTML = this.template()

		this.#submitBtn = this.querySelector("#login-submit")
		this.#loginLink = this.querySelector("#login-link")

		this.registerEvents();
	}


	display(){
	}

	template(){
		return `
			<div class="flex flex-col text-center bg-[var(--bg-med)] p-6 rounded-2xl shadow-2xl w-11/12 max-w-sm md:max-w-md mx-auto"> 
				<h1 class="text-3xl font-extrabold mb-4 text-[var(--text-color)]">Registrazione</h1>
				<p class="mb-6 text-sm text-[var(--text-muted)]">Sei già registrato?
					<a id="login-link" class="text-[var(--accent)] hover:underline cursor-pointer font-medium">Login</a>.
				</p>
				<div id="login-fields" class="flex flex-col items-center space-y-5 px-0">
					<input 
						type="email" 
						id="register-email" 
						placeholder="Email" 
						required 
						class="w-full p-3 bg-[var(--bg-dark)] border border-[var(--border-color)] text-[var(--text-primary)] shadow-inner rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] text-black placeholder-gray-500 transition duration-150 ease-in-out">

					<input 
						type="text" 
						id="register-name" 
						placeholder="Nome" 
						required 
						class="w-full p-3 bg-[var(--bg-dark)] border border-[var(--border-color)] text-[var(--text-primary)] shadow-inner rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] text-black placeholder-gray-500 transition duration-150 ease-in-out">

					<input 
						type="password" 
						id="register-password" 
						placeholder="Password" 
						required 
						class="w-full p-3 bg-[var(--bg-dark)] border border-[var(--border-color)] text-[var(--text-primary)] shadow-inner rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] text-black placeholder-gray-500 transition duration-150 ease-in-out">
				
						<input 
						type="password" 
						id="register-password-confirm" 
						placeholder="Conferma password" 
						required 
						class="w-full p-3 bg-[var(--bg-dark)] border border-[var(--border-color)] text-[var(--text-primary)] shadow-inner rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent)] text-black placeholder-gray-500 transition duration-150 ease-in-out">
				
					<button id="login-submit" class="w-full mt-4 p-3 bg-[var(--bg-light)] text-[var(--text-primary)] font-bold rounded-lg hover:bg-[var(--accent)] transition duration-150 ease-in-out">
						Registrati
					</button>
				</div>
			</div>`
	
	}


	registerEvents(){
		this.#handleSubmit()
		this.#handleRegisterRouting()
	}

	#handleSubmit(){
		this.#submitBtn.addEventListener("click", (event) => {
			event.preventDefault();

			const email = this.querySelector("#login-email").value;
			const password = this.querySelector("#login-password").value;
			const submitEvent = new CustomEvent(Events.REGISTER_SUBMIT_EVENT, {
				bubbles: true, 
				detail: {
					email: email,
					password: password
				}
			})
			this.dispatchEvent(submitEvent)
		})
	}

	#handleRegisterRouting(){
		this.#loginLink.addEventListener("click", (e) => {
			const event = new CustomEvent("login-routing", {
				bubbles: true
			})

			this.dispatchEvent(event)
		})
	}

}

customElements.define("register-view", RegisterView);
