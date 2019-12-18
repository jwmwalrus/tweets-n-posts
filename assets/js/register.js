import 'dist/js/app.js';

import {
    HandleFetch,
    getToken,
    handleLinks,
    removePayload,
    visitPage,
} from './_utils';

$(document).ready(function() {
    removePayload();
    handleLinks();
    $('#register-link').hide();
});

window.handleSubmit = (event) => {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const repeatPassword = document.getElementById('repeat-password').value;

    if (password !== repeatPassword) {
        $('.alert-warning').show();
        return false;
    }

    const form = document.getElementById('register-form');
    const fd = new FormData(form);

    fetch(Routing.generate('api_register'), { method: 'POST', body: fd })
        .then(HandleFetch.response)
        .then(() => {
            getToken({ id: username, password })
                .then(() => { visitPage(Routing.generate('home')); })
                .catch(() => { $('.alert-danger').show(); });
        })
        .catch(HandleFetch.catch);

    return false;
};

$('#username').on('blur', (event) => {
    // TODO: remove unwsanted charactres
});

$('#repeat-password').on('blur', () => {
    const password = document.getElementById('password').value;
    const repeatPassword = document.getElementById('password').value;

    if (password !== repeatPassword) {
        $('.alert-warning').show();
    } else {
        $('.alert-warning').hide();
    }
});
