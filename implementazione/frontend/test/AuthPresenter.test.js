import { describe, test, expect, vi, beforeEach, afterEach } from "vitest";

import AuthPresenter from "../js/auth/AuthPresenter.js";
import View from "../js/interfaces/View.js";
import APIService from "../js/interfaces/APIService.js";
import Events from "../js/utility/Events.js";
import { eventBus } from "../js/utility/DefaultObserver.js";
import RegisterView from "../js/auth/RegisterView.js";

class MockView extends View {
	constructor(){
		super();
	}
	display(data) {
		this.data = data;
	}
}


class MockRegisterView extends RegisterView {
	constructor(){
		super();
	}
	display(data) {
		this.data = data;
	}
}


export class MockService extends APIService {
	constructor() {
		super();
	}
	
	login(email, password) {
		return Promise.resolve({ success: true });
	}
	
	register(name, email, password) {
		return Promise.resolve({ success: true });
	}
}

customElements.define("mock-view", MockView);
customElements.define("mock-register-view", MockRegisterView);

describe("AuthPresenterTest", () => {
	var presenter;
	var view;
	var service;
	
	beforeEach(() => {
		view = new MockView();
		service = new MockService();
		presenter = new AuthPresenter(view, service);
		presenter.init();
	});

    afterEach(() => {
        eventBus.clear();
    });

	test("input login validato correttamente", async () => {
		const data = { email: "test@test.com", password: "123456" };
		const displaySpy = vi.spyOn(view, "display");

		eventBus.notify(Events.AUTH_SUBMIT_EVENT, data);

		await vi.waitFor(() => {
			expect(displaySpy).toHaveBeenCalled();
			expect(displaySpy).toHaveBeenCalledWith(data);
			expect(view.data).toEqual(data);
		});
	})


	test("input login vuoto", async () => {
		const data = { email: "", password: "" };
		const expected = {error: "dati inseriti non validi"};
		const displaySpy = vi.spyOn(view, "display");

		eventBus.notify(Events.AUTH_SUBMIT_EVENT, null);

		await vi.waitFor(() => {
			expect(displaySpy).toHaveBeenCalled();
			expect(displaySpy).toHaveBeenCalledWith(expected);
			expect(view.data).toEqual(expected);
		});
	})

	test("input login campo email vuoto", async () => {
		const data = { email: "", password: "123456" };
		const expected = {error: "email o password sono vuoti"};
		const displaySpy = vi.spyOn(view, "display");

		eventBus.notify(Events.AUTH_SUBMIT_EVENT, data);

		await vi.waitFor(() => {
			expect(displaySpy).toHaveBeenCalled();
			expect(displaySpy).toHaveBeenCalledWith(expected);
			expect(view.data).toEqual(expected);
		});
	})
	

	test("input login campo password vuoto", async () => {
		const data = { email: "test@test.com", password: "" };
		const expected = {error: "email o password sono vuoti"};
		const displaySpy = vi.spyOn(view, "display");

		eventBus.notify(Events.AUTH_SUBMIT_EVENT, data);

		await vi.waitFor(() => {
			expect(displaySpy).toHaveBeenCalled();
			expect(displaySpy).toHaveBeenCalledWith(expected);
			expect(view.data).toEqual(expected);
		});
	})

	test("input register password non corrispondono", async () => {
        view = new MockRegisterView();
        presenter = new AuthPresenter(view, service);
        presenter.init();

		const data = { email: "test@test.com", password: "123456", passwordConfirm: "12", name: "test" };
		const expected = {error: "le password non corrispondono"};
		const displaySpy = vi.spyOn(view, "display");

		eventBus.notify(Events.AUTH_SUBMIT_EVENT, data);

		await vi.waitFor(() => {
			expect(displaySpy).toHaveBeenCalled();
			expect(view.data).toEqual(expected);
		});
	})


	
	
		
	

	
});
