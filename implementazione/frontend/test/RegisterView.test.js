import { describe, test, expect, vi, beforeEach, afterEach } from "vitest";

import RegisterView from "../js/auth/RegisterView.js";
import Events from "../js/utility/Events.js";
import Routes from "../js/utility/Routes.js";
import { eventBus } from "../js/utility/DefaultObserver.js";


describe("LoginViewTest", () => {
	let element;
	
	beforeEach(() => {
		element = new RegisterView();
		document.body.appendChild(element);
		element.connectedCallback();
	});
	
	afterEach(() => {
		document.body.innerHTML = "";
        eventBus.clear();
	});
	
	test("elemento visualizzato correttamente", () => {
		expect(element.querySelector("#auth-submit")).not.toBeNull();
		expect(element.querySelector("#register-email")).not.toBeNull();
		expect(element.querySelector("#register-name")).not.toBeNull();
		expect(element.querySelector("#register-password")).not.toBeNull();
		expect(element.querySelector("#register-password-confirm")).not.toBeNull();
	});
	
	test("emette AUTH_SUBMIT_EVENT al click di submit", () => {
		const handler = vi.fn();
		const unsubscribe = eventBus.subscribe(Events.AUTH_SUBMIT_EVENT, handler);
		
		element.querySelector("#register-email").value = "test@test.com";
		element.querySelector("#register-password").value = "123456";
		element.querySelector("#register-password-confirm").value = "123456";
		element.querySelector("#register-name").value = "test";
		
		element.querySelector("#auth-submit").click();
		
		const data = handler.mock.calls[0][0];
		
		expect(data).toEqual({
			email: "test@test.com",
			password: "123456",
			passwordConfirm: "123456",
			name: "test"
		});
		unsubscribe();
	});
	
	test("emette ROUTING_EVENT al click di register", () => {
		const handler = vi.fn();
		const unsubscribe = eventBus.subscribe(Events.ROUTING_EVENT, handler);
		
		element.querySelector("#login-link").click();
		
		const data = handler.mock.calls[0][0];
		
		expect(data).toEqual({
			route: Routes.LOGIN
		});
		unsubscribe();
	});

	test("display mostra messaggio di errore", () => {
		element.display({error: "test"});
		expect(element.querySelector("#error").textContent).toBe("test");
	});
});
