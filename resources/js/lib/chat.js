import {buildQuery, forEach, isEmpty} from "../utils";
const CHAT_URL = process.env.MIX_CHAT_URL;

export function chatAddMessageListener(cb, cbReload = null, getMessages = null) {
    let errorCount = 0;
    const loop = async () => {
        try {
            const messages = getMessages ? getMessages() : [];
            const cid = messages.length > 0 ? messages[0].cid : '';
            const last10 = messages.slice(Math.max(messages.length - 10, 1)).reverse().map(m => m.id);
            const res = await chat$post('/v1/polling', {timeout: 30000, hash: last10.join(','), cid});
            if (res.code === 301) {
                console.log('Polling: no data, next run');
                setTimeout(loop, res.nextRun);
            } else if (res.code === 200) {
                cb(res.data);
                setTimeout(loop, 100);
            } else if (res.code === 201) {
                if (cbReload) {
                    cbReload(res.cid);
                }
                setTimeout(loop, 100);
            } else {
                throw new Error('Invalid polling response')
            }
        } catch (err) {
            console.error(err);
            errorCount++;
            if (errorCount > 100) {
                alert('Please reload and retry');
                return;
            }
            setTimeout(loop, 5000);
        }
    };

    loop();
}

export async function chat$get(uri, params = {}) {

    uri = CHAT_URL + uri;
    let url =  uri + '?' + buildQuery(params);

    return fetch(url).then(response => {
        return response.json();
    });
}

export async function chat$post(uri, params = {}, query = {}) {

    let url =  CHAT_URL + uri;
    if (!params) {
        params = {};
    }

    if (window.$chatJwt) {
        params._token = window.$chatJwt;
    }

    if (!isEmpty(query)) {
        url +=  '?' + buildQuery(query);
    }

    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(params),
    })
        .then((response) => {
            return response.json();
        })
}

export function chat$upload(uri, photo, params = {}) {
    let url =  CHAT_URL + uri;

    const formData = new FormData();

    if (window.$chatJwt) {
        params._token = window.$chatJwt;
    }

    for (const [k,v] of Object.entries(params)) {
        formData.append(k, v);
    }

    formData.append('photo', photo);

    return fetch(url, {
        method: 'POST',
        body: formData
    })
        .then((response) => response.json())
        .catch((error) => {
            console.error('Error:', error);
        });
}

