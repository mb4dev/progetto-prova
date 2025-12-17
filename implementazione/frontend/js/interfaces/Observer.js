export default class Observer {

    constructor(){
        this._listeners =  new Map();
    }
    subscribe(event, callback) { throw new Error("subscribe non implementato");}
    unsubscribe(event, callback) { throw new Error("unsubscribe non implementato");}
    notify(event, data) { throw new Error("notify non implementato");}
    clear() { throw new Error("clear non implementato");}
}