import Command from "../interfaces/Command.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

export default class ConfirmPaymentCommand extends Command {
    constructor() {
        super();

    }

    execute(data) {
		console.log("confermato pagamento")
    }
}
