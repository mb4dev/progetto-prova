const Routes = {
	REGISTER: "register",
	LOGIN: "login",
	HOME: "home",
	MAIN: "main",

	MAIN_PROFILE : "main:profile",
	MAIN_CAMPI : "main:campi",
	MAIN_CORSI : "main:corsi",
	MAIN_ABBONAMENTO : "main:abbonamento",
	MAIN_STORICO : "main:storico",
	MAIN_PAYMENT_SINGLE: "main:payment:single",
	MAIN_PAYMENT_SUBSCRIPTION: "main:payment:subscription",
	// alias legacy per compatibilità, se usato viene mappato al pagamento singolo
	MAIN_PAYMENT: "main:payment:single",
	
	MAIN_CALENDARIO: "main:calendario"
}

export default Routes;