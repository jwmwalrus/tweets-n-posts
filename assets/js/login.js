import 'dist/js/app.js';

import '../scss/signin.scss';

import {
    HandleFetch,
    getToken,
    handleLinks,
    removePayload,
    visitPage,
} from './_utils';

$(document).ready(function() {
    removePayload();
    handleLinks('login');
    $('#login-link').hide();
});

window.handleSubmit = (event) => {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const form = document.getElementById('login-form');
    const fd = new FormData(form);

    fetch(Routing.generate('api_login'), { method: 'POST', body: fd })
        .then(HandleFetch.response)
        .then(() => {
            getToken({ id: username, password })
                .then(() => { visitPage(Routing.generate('home')); })
                .catch(() => { $('.alert').removeAttr('hidden'); });
        })
        .catch(HandleFetch.catch);

    return false;
};
