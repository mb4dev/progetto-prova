const Events = {
	AUTH_SUBMIT_EVENT : "auth:submit",
	AUTH_NAVIGATE_EVENT: "auth:navigate",
	ROUTING_EVENT : "app:navigate",
	MAIN_NAVIGATE : "main:navigate",
	PROFILE_UPDATE_EVENT: "profile:update",

	SPORT_SELECTED_EVENT: "sport:selected",
	SPORTS_LOAD_EVENT: "sports:load",

	CALENDAR_LOAD_EVENT : "calendar:load",
	SLOT_SELECTED_EVENT : "slot:selected",
	DATE_SELECTED_EVENT: "date:selected",
	DATE_INCREMENT_EVENT: "date:increment",
	DATE_DECREMENT_EVENT: "date:decrement",
	RESUME_CLEAR: "resume:clear",

	PAYMENT_PROCEED_EVENT: "payment:start",
	
	CART_UPDATED: "cart:updated",
	CART_REMOVE: "cart:remove-item",
	CART_CLEAR: "cart:clear",

}

export default Events