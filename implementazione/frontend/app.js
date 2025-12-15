class MockBackend {
    #token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSm9obiBEb2UiLCJhZG1pbiI6ZmFsc2UsImlhdCI6MTUxNjIzOTAyMn0.ISViWxHK_jvGDtUR6q8cwU9HRXz522lKxg-jtM00sYM"
    login(email, password){
        return this.#token;
    }
}

import LoginView from "./js/auth/LoginView.js"
import LoginPresenter from "./js/auth/LoginPresenter.js"

document.addEventListener("DOMContentLoaded", () => {
    const app = document.getElementById("app");
    const loginView = new LoginView()
    app.appendChild(loginView)
    
    const presenter = new LoginPresenter(loginView);
})











