import Command from "../interfaces/Command.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import { apiService } from "../utility/MockAPIService.js";

export default class ProfileUpdateCommand extends Command {
    constructor() {
        super();
    }

    async execute(data) {
		try {
			//const response = await apiService.updateProfile(data);
			//if (!response.success) throw new Error(response.message || "Errore aggiornamento");
			
			const existingUser = JSON.parse(localStorage.getItem("user"));
			const newUser = { ...existingUser, ...data }
			localStorage.setItem("user", JSON.stringify(newUser));
			console.log(newUser)
			return newUser;
		}
		catch(e){

		}

    }
}
