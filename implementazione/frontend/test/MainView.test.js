import { describe, test, expect, vi, beforeEach, afterEach } from "vitest";

import MainView from "../js/main/MainView.js";
import Events from "../js/utility/Events.js";
import Routes from "../js/utility/Routes.js";
import { eventBus } from "../js/utility/DefaultObserver.js";



describe("MainViewTest", () => {
	let element;
	
	beforeEach(() => {
		element = new MainView();
		document.body.appendChild(element);
		element.connectedCallback();
	});

	afterEach(() => {
		document.body.innerHTML = "";
        eventBus.clear();
	});

	test("main view visualizzata correttamente", () => {
		expect(element.querySelector("#side-menu")).not.toBeNull();
		expect(element.querySelector("#main-content")).not.toBeNull();
	})


	test("test click menu laterale", () => {
		const handler = vi.fn();
		eventBus.subscribe(Events.MAIN_SELECT_EVENT, handler);
		
		const btn = element.querySelector("#side-menu-campi");
		btn.click();

		expect(handler).toHaveBeenCalledWith({
			main: Routes.MAIN_CAMPI
		});
	});

	test("display deve caricare la view nel main-content", () => {
		const newView = document.createElement("div");
		newView.id = "test-view";
		newView.textContent = "test";

		element.display({ view: newView });

		const mainContent = element.querySelector("#main-content");
		expect(mainContent.contains(newView)).toBe(true);
		expect(mainContent.innerHTML).toContain("test");
	});

	test("display deve aggiornare il tab attivo", () => {
		const campiBtn = element.querySelector("#side-menu-campi");
		const corsiBtn = element.querySelector("#side-menu-corsi");

		expect(campiBtn.classList.contains("bg-[var(--bg-light)]")).toBe(false);

		element.display({ route: Routes.MAIN_CAMPI });

		expect(campiBtn.classList.contains("bg-[var(--bg-light)]")).toBe(true);
		expect(campiBtn.classList.contains("text-black")).toBe(true);
		expect(corsiBtn.classList.contains("bg-[var(--bg-light)]")).toBe(false);


		element.display({ route: Routes.MAIN_CORSI });

		expect(campiBtn.classList.contains("bg-[var(--bg-light)]")).toBe(false);
		expect(corsiBtn.classList.contains("bg-[var(--bg-light)]")).toBe(true);
	});
});