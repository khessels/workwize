function subscribe(eventName, listener) {
    document.addEventListener(eventName, listener);
}

function unsubscribe(eventName, listener) {
    document.removeEventListener(eventName, listener);
}

function publish(eventName, data) {

    if(data) {
        const Event = new CustomEvent(eventName, {detail: data});
        document.dispatchEvent(Event);
    }else{
        const Event = new CustomEvent(eventName);
        document.dispatchEvent(Event);
    }
}

export { publish, subscribe, unsubscribe};
