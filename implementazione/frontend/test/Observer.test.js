import { describe, test, expect, vi, beforeEach, afterEach } from "vitest";
 
import DefaultObserver  from "../js/utility/DefaultObserver";
import Events  from "../js/utility/Events";

describe("ObserverTest", () => {
	
	let observer;
	beforeEach(() => {
		observer = new DefaultObserver();
	});
	
	afterEach(() => {
		observer.clear();
	});
	
	test("subscribe inserito correttamente", () => {
		const handler = vi.fn();
		const unsubscribe = observer.subscribe(Events.AUTH_SUBMIT_EVENT, handler);
		expect(unsubscribe).toBeDefined();
		unsubscribe();
	});

	test("subscribe paramentro callbacksbagliato lancia eccezione", () => {
		expect(() => observer.subscribe(Events.AUTH_SUBMIT_EVENT, "test")).toThrow();
	})

	test("subscribe parametro callback corretto", () => {
		const handler = vi.fn();	
		expect(() => observer.subscribe(Events.AUTH_SUBMIT_EVENT, handler)).not.toThrow();
	})

	test("notify esegue la callback correttamente", () => {
		const handler = vi.fn();
		observer.subscribe(Events.AUTH_SUBMIT_EVENT, handler);

		const expectedData = {data: "test"};	
		observer.notify(Events.AUTH_SUBMIT_EVENT, expectedData);
		expect(handler).toHaveBeenCalledWith(expectedData);

	})

		test("notify chiama solo la callback assocaita all'evento", () => {
		const handler = vi.fn();
		observer.subscribe(Events.AUTH_SUBMIT_EVENT, handler);
		observer.subscribe(Events.ROUTING_EVENT, handler);

		const expectedData = {data: "test"};	
		observer.notify(Events.AUTH_SUBMIT_EVENT, expectedData);
		expect(handler).toHaveBeenCalledTimes(1);
		expect(handler).toHaveBeenCalledWith(expectedData);

	})
});