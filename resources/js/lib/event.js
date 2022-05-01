
const listeners = {

};

export function addListener(name, callback) {
    listeners[name] = listeners[name] || [];
    listeners[name].push(callback);
}

export function removeListener(name, callback) {
    listeners[name] = listeners[name] || [];
    const index = listeners[name].indexOf(callback);
    if (index > - 1) {
        listeners[name].splice(index, 1);
    }
}

export function emitListener(name, data) {
    if (listeners[name]) {
        listeners[name].forEach(cb => {
            cb(data);
        })
    }
}
