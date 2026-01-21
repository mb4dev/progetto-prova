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
	PAYMENT_CANCEL_EVENT: "payment:cancel",
	PAYMENT_CONFIRM_EVENT: "payment:confirm",

	HISTORY_LOAD_EVENT: "history:load",
	HISTORY_DELETE_EVENT: "history:delete",

	SUBSCRIPTION_LOAD_EVENT: "subscription:load",
	SUBSCRIPTION_SELECTED_EVENT: "subscription:selected",

	CART_UPDATED: "cart:updated",
	CART_REMOVE: "cart:remove",
	CART_CLEAR: "cart:clear",
	CART_REMOVE_SUBSCRIPTION: "cart:remove-subscription",

	/*
	ADMIN_ADD_FIELD: "admin:add:field",
	ADMIN_EDIT_FIELD: "admin:edit:field",
	ADMIN_DELETE_FIELD: "admin:delete:field",
	ADMIN_ADD_SPORT: "admin:add:sport",
	ADMIN_EDIT_SPORT: "admin:edit:sport",
	ADMIN_DELETE_SPORT: "admin:delete:sport",
	*/

}

export default Events