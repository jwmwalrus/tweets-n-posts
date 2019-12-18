import { STATUS_CODES } from 'http';
import { capitalize } from 'lodash';

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

export class Fetcher {
    static async getResponse(resource, options) {
        let response;
        try {
            await checkToken();
            response = await fetch(resource, options);
        } catch (e) {
            if (e.message === ERROR_TOKEN_NOT_FOUND
                || e.message === ERROR_TOKEN_EXPIRED) {
                if (window.location.pathname.indexOf('/customers') === 0) {
                    visitPage(Routing.generate('customers_landing', { open: 1 }));
                } else {
                    visitPage(Routing.generate('users_login'));
                }
            }
            throw e;
        }

        if (!response.ok) {
            throw new Error(response.status.toString());
        } else {
            const contentType = response.headers.get('content-type');
            if (contentType) {
                if (contentType.indexOf('application/json') !== -1) {
                    const result = await response.json();
                    return result;
                }
                if (contentType.indexOf('text/plain') !== -1) {
                    const result = response.text();
                    return result;
                }
            }
            return Promise.resolve();
        }
    }

    static async noContent(resource, options) {
        let response;
        try {
            await checkToken();
            response = await fetch(resource, options);
        } catch (e) {
            if (e.message === ERROR_TOKEN_NOT_FOUND
                || e.message === ERROR_TOKEN_EXPIRED) {
                if (window.location.pathname.indexOf('/customers') === 0) {
                    visitPage(Routing.generate('customers_landing', { open: 1 }));
                } else {
                    visitPage(Routing.generate('users_login'));
                }
                return;
            } else {
                throw e;
            }
        }
        if (!response.ok) {
            throw Error(response.status.toString());
        }
    }

    static catch(e) {
        HandleFetch.catch(e);
    }

    static formatError(e) {
        return HandleFetch.formatError(e);
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

export function handleLinks() {
    checkToken()
        .then(() => {
            $('#login-link').hide();
            $('#logout-link').show();
            $('#register-link').hide();
            // $('#user-page-link').show();
        })
        .catch(() => {
            $('#login-link').show();
            $('#logout-link').hide();
            $('#register-link').show();
        });
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
