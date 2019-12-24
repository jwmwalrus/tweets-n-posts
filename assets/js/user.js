/* global USER_ID
 */
import 'dist/js/app.js';

import paginate from './_pagination';

import '../scss/user.scss';

import {
    HandleFetch,
    checkToken,
    handleLinks,
} from './_utils';

var statusOpen = false;
var tweetsList = [];

$(document).ready(function() {
    $('.sidebar').hide();

    handleLinks();

    (async () => {
        try {
            const payload = await checkToken();

            if (USER_ID === Number(payload.id)) {
                $('#user-page-link').hide();
            } else {
                $('.editable-post').hide();
            }

            const response = await fetch(
                Routing.generate('api_tweets_list', { norefresh: 1 }),
            );
            tweetsList = await HandleFetch.response(response);

            updateTweetsRoot();
        } catch (e) {
            $('#user-page-link').hide();
            $('.editable-post').hide();
        }
    })();


    paginate();
});

function updateTweetsRoot() {
    const root = document.getElementById('tweets-root');
    tweetsList.forEach((e) => {
        const card = document.createElement('div');
        card.setAttribute('class', 'card');

        const header = document.createElement('div');
        header.setAttribute('class', 'card-header');

        const author = document.createElement('div');
        author.setAttribute('class', 'row');
        author.innerText = `${e.owner.name} (@${e.owner.twitterid})`;
        header.appendChild(author);

        const stamp = document.createElement('div');
        stamp.setAttribute('class', 'row');
        const dt = new Date(e.timestamp * 1000).toLocaleString('en-US');
        stamp.innerText = dt;
        header.appendChild(stamp);

        card.appendChild(header);

        const body = document.createElement('div');
        body.setAttribute('class', 'row');
        body.innerHTML = e.raw;
        card.appendChild(body);

        root.appendChild(card);
    });
}

window.toggleSidebar = function() {
    if (statusOpen) {
        $('.sidebar').hide();
        $('#btn-sidebar-open').show();
    } else {
        $('.sidebar').show();
        $('#btn-sidebar-open').hide();
    }

    statusOpen = !statusOpen;
};
