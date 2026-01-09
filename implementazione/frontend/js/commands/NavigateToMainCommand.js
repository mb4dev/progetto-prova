import Command from "../interfaces/Command.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import Routes from "../utility/Routes.js";

<<<<<<< HEAD
export default class NavigateToMainCommand extends Command {
    execute() {
        eventBus.notify(Events.ROUTING_EVENT, { route: Routes.MAIN });
    }
}
=======

export default class NavigateToMainCommand extends Command {
    execute(){
        console.log("success")
        eventBus.notify(Events.ROUTING_EVENT, { route: Routes.MAIN });
    }
}
>>>>>>> dev
