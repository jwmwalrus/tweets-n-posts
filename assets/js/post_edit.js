/* global POST_ID, USER_ID
 */

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
});

window.handleSubmit = (event) => {
    event.preventDefault();

    const form = document.getElementById('edit-post-form');
    const fd = new FormData(form);

    (async () => {
        try {
            await checkToken();
        } catch (e) {
            visitPage(Routing.generate('login'));
            return;
        }

        try {
            const response = await fetch(
                Routing.generate('api_posts_edit', { id: POST_ID }),
                { method: 'POST', body: fd },
            );

            await HandleFetch.response(response);

            visitPage(Routing.generate('user_show', { id: USER_ID }));
        } catch (e) {
            HandleFetch.catch(e);
        }
    })();

    return false;
};
