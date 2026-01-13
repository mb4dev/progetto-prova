import Command from "../interfaces/Command.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";

export default class NavigateCommand extends Command {
    constructor(route) {
        super();
        this.route = route;
    }

    execute() {
        if (this.route && this.route.startsWith("main:")) {
            eventBus.notify(Events.MAIN_NAVIGATE, { main: this.route });
        } else {
            eventBus.notify(Events.ROUTING_EVENT, { route: this.route });
        }
    }
}
