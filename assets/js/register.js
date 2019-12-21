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
    handleLinks();
    $('#register-link').hide();
});

window.handleSubmit = (event) => {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const repeatPassword = document.getElementById('repeat-password').value;

    if (password !== repeatPassword) {
        $('.alert-warning').removeAttr('hidden');
        return false;
    }

    const form = document.getElementById('register-form');
    const fd = new FormData(form);

    fetch(Routing.generate('api_register'), { method: 'POST', body: fd })
        .then(HandleFetch.response)
        .then(() => {
            getToken({ id: username, password })
                .then(() => { visitPage(Routing.generate('home')); })
                .catch(() => { visitPage(Routing.generate('login')); });
        })
        .catch(() => { $('.alert-danger').removeAttr('hidden'); });

    return false;
};

$('#username').on('blur', (event) => {
    const id = event.target.id;
    const str = $(`#${id}`).val().replace(/[^A-Za-z0-9\._]+/g, '');
    $(`#${id}`).val(str);
});

$('#repeat-password').on('blur', (event) => {
    const password = document.getElementById('password').value;
    const repeatPassword = event.target.value;
    console.log('PASSWORD: ', password);
    console.log('REPEAT PASSWORD: ', repeatPassword);

    if (password !== repeatPassword) {
        $('.alert-warning').removeAttr('hidden');
    } else {
        $('.alert-warning').attr('hidden');
    }
});
