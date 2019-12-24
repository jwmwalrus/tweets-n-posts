import { STATUS_CODES } from 'http';

const LOCAL_STORAGE_PAYLOAD_KEY = 'tweets-n-posts-payloaad';

export const ERROR_TOKEN_NOT_FOUND = 'TOKEN-NOT-FOUND';
export const ERROR_TOKEN_EXPIRED = 'TOKEN-EXPIRED';
export const ERROR_CREDENTIALS_REQUIRED = 'CREDENTIALS-REQUIRED';

export class HandleFetch {
    static catch(e) {
        if (Number(e.message) > 0) {
            console.error('HTTP Status ' + e.message + ': ' + STATUS_CODES[e.message]);
        }

        console.error(e);
    }

    static formatError(e) {
        if (Number(e.message) > 0) {
            return 'HTTP Status ' + e.message + ': ' + STATUS_CODES[e.message];
        }

        return e.message;
    }

    static response(response) {
        if (!response.ok) {
            throw Error(response.status.toString());
        } else {
            const contentType = response.headers.get('content-type');
            if (contentType) {
                if (contentType.indexOf('application/json') !== -1) {
                    return response.json();
                }
                if (contentType.indexOf('text/plain') !== -1) {
                    return response.text();
                }
            }
            return Promise.resolve();
        }
    }
}

export async function checkToken() {
    let payload = null;
    try {
        const string = localStorage.getItem(LOCAL_STORAGE_PAYLOAD_KEY);
        if (string && string !== 'undefined') {
            payload = JSON.parse(string);
        }
    } catch (e) {
        throw e;
    }

    if (!payload) {
        throw new Error(ERROR_TOKEN_NOT_FOUND);
    }

    const now = Math.floor(Date.now() / 1000);
    if (payload.exp - now < 100) {
        throw new Error(ERROR_TOKEN_EXPIRED);
    }

    return payload;
}

export async function getToken(credentials = {}) {
    let payload;
    try {
        payload = await checkToken();
        if (!credentials.id || credentials.id !== payload.username) {
            throw new Error(ERROR_CREDENTIALS_REQUIRED);
        }
    } catch (e) {
        if (e.message === ERROR_CREDENTIALS_REQUIRED) {
            throw e;
        }

        payload = await fetchToken(credentials);
    }

    return payload;
}

export function getPayloadFromToken(token) {
    const base64Url = token.split('.')[1];

    return atob(base64Url);
}

export async function handleLinks(from) {
    try {
        await checkToken();
        switch (from) {
            case 'home':
                $('#logout-link').removeAttr('hidden');
                $('#user-page-link').removeAttr('hidden');
                break;
            case 'user':
                $('#sidebar-toggle').removeAttr('hidden');
                $('#home-link').removeAttr('hidden');
                $('#logout-link').removeAttr('hidden');
                break;
            case 'post':
                $('#home-link').removeAttr('hidden');
                $('#logout-link').removeAttr('hidden');
                $('#user-page-link').removeAttr('hidden');
                break;
            default:
        }
    } catch (e) {
        switch (from) {
            case 'home':
                $('#login-link').removeAttr('hidden');
                $('#register-link').removeAttr('hidden');
                break;
            case 'user':
                $('#sidebar-toggle').removeAttr('hidden');
                $('#home-link').removeAttr('hidden', 'hidden');
                $('#login-link').removeAttr('hidden', 'hidden');
                $('#register-link').removeAttr('hidden', 'hidden');
                break;
            case 'login':
                $('#home-link').removeAttr('hidden', 'hidden');
                $('#register-link').removeAttr('hidden', 'hidden');
                break;
            case 'register':
                $('#home-link').removeAttr('hidden', 'hidden');
                $('#login-link').removeAttr('hidden', 'hidden');

                break;
            default:
        }
    }
}

export async function removePayload() {
    localStorage.removeItem(LOCAL_STORAGE_PAYLOAD_KEY);
}

export function splitHash(hash) {
    const cleanHash = hash.replace('#', '');
    const items = cleanHash.split('/');

    return items.filter((a) => a !== '');
}

export function visitPage(page) {
    if (page === '#') {
        return;
    }

    window.location.href = page;
}

window.goToUserPage = function() {
    checkToken()
        .then((payload) => {
            const page = Routing.generate('user_show', { id: payload.id });
            visitPage(page);
        })
        .catch(console.error);
};

async function fetchToken(credentials = {}) {
    let basic;
    if (credentials.basic) {
        basic = credentials.basic;
    } else if (credentials.id && credentials.password) {
        basic = btoa(`${credentials.id}:${credentials.password}`);
    } else {
        return new Error(ERROR_CREDENTIALS_REQUIRED);
    }

    try {
        const response = await fetch(Routing.generate('tokens'), {
            method: 'POST',
            headers: { Authorization: 'Basic ' + basic },
        });

        const data = await HandleFetch.response(response);

        const string = getPayloadFromToken(data.token);
        localStorage.setItem(LOCAL_STORAGE_PAYLOAD_KEY, string);

        return JSON.parse(string);
    } catch (e) {
        throw e;
    }
}
