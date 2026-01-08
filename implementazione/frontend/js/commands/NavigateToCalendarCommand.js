import Command from "../interfaces/Command.js";
import { eventBus } from "../utility/DefaultObserver.js";
import Events from "../utility/Events.js";
import Routes from "../utility/Routes.js";


export default class NavigateToCalendarCommand extends Command {
    execute(){
        eventBus.notify(Events.MAIN_NAVIGATE, { main: Routes.MAIN_CALENDARIO });
    }
}