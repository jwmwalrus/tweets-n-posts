import 'dist/js/app.js';

import '../scss/post.scss';

import {
    HandleFetch,
    checkToken,
    handleLinks,
    visitPage,
} from './_utils';

$(document).ready(function() {
    handleLinks('post');

    (async () => {
        try {
            const payload = await checkToken();
            $('#user-name').text(payload.name);
        } catch (e) {
            visitPage(Routing.generate('login'));
        }
    })();
});

window.handleSubmit = (event) => {
    event.preventDefault();

    const form = document.getElementById('new-post-form');
    const fd = new FormData(form);

    (async () => {
        try {
            const payload = await checkToken();
            fd.append('user_id', payload.id);

            const response = await fetch(
                Routing.generate('api_posts_new'),
                { method: 'POST', body: fd },
            );

            await HandleFetch.response(response);

            visitPage(Routing.generate('user_show', { id: payload.id }));
        } catch (e) {
            $('.alert-danger').show();
            HandleFetch.catch(e);
        }
    })();

    return false;
};
