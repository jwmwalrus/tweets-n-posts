/* global USER_ID
 */
import 'dist/js/app.js';

import paginate from './_pagination';

import '../scss/user.scss';

import {
    HandleFetch,
    checkToken,
    handleLinks,
    visitPage,
} from './_utils';

const TWEETS_VIEW_ALL = 1;
const TWEETS_VIEW_MINE = 2;
const TWEETS_VIEW_HIDDEN = 3;

var payload = { id: 0 };
var statusOpen = false;
var tweetsList = [];


$(document).ready(function() {
    handleLinks('user');

    (async () => {
        try {
            payload = await checkToken();

            if (USER_ID === Number(payload.id)) {
                $('#post-new').removeAttr('hidden');
            } else {
                $('#user-page-link').removeAttr('hidden');
                $('.editable-post').hide();
            }
        } catch (e) {
            $('.editable-post').hide();
        }

        updateTweetsList(1);
    })();


    paginate();
});

async function patchTweet(id, body) {
    try {
        await checkToken();
    } catch (e) {
        visitPage(Routing.generate('login'));
        return;
    }

    try {
        const response = await fetch(
            Routing.generate('api_tweets_patch', { id }),
            { method: 'PATCH', body },
        );

        await HandleFetch.response(response);
    } catch (e) {
        HandleFetch.catch(e);
    }
}

async function updateTweetsList(noRefresh = 0) {
    try {
        const response = await fetch(
            Routing.generate('api_tweets_list', { norefresh: noRefresh }),
        );

        tweetsList = await HandleFetch.response(response);

        updateTweetsRoot();
    } catch (e) {
        HandleFetch.catch(e);
    }
}

async function updateTweetsRoot() {
    const tweetsFilter = Number($('#tweets-filter').find(':selected').val());

    const root = document.getElementById('tweets-root');
    root.innerHTML = '';

    tweetsList.forEach((e) => {
        switch (tweetsFilter) {
            case TWEETS_VIEW_ALL:
                if (e.hidden) {
                    return;
                }
                break;
            case TWEETS_VIEW_MINE:
                if (e.hidden) {
                    return;
                }
                if (!(e.owner.id === USER_ID && e.owner.id === payload.id)) {
                    return;
                }
                break;
            case TWEETS_VIEW_HIDDEN:
                if (!e.hidden) {
                    return;
                }
                if (!(e.owner.id === USER_ID && e.owner.id === payload.id)) {
                    return;
                }
                break;
            default:
        }

        const card = document.createElement('div');
        card.id = `tweet-card-${e.id}`;
        card.setAttribute('class', 'card card-tweet my-4');

        const header = document.createElement('div');
        header.setAttribute('class', 'card-header card-header-tweet');

        const author = document.createElement('div');
        author.setAttribute('class', 'row');
        author.innerHTML = `<strong>${e.owner.name} (@${e.owner.twitterid})</strong>`;
        header.appendChild(author);

        const stamp = document.createElement('div');
        stamp.setAttribute('class', 'row float-right');
        const dt = new Date(e.timestamp * 1000).toLocaleString('en-US');
        stamp.innerHTML = `<small>${dt}</small>`;
        header.appendChild(stamp);

        if (!e.hidden
            && [TWEETS_VIEW_ALL, TWEETS_VIEW_MINE].includes(tweetsFilter)
            && (e.owner.id === USER_ID && e.owner.id === payload.id)) {
            const hide = document.createElement('div');
            hide.setAttribute('class', 'row');
            hide.innerHTML = `<button class="btn" onclick="hideTweet(${e.id});">Hide</button>`;
            header.appendChild(hide);
        }

        if (e.hidden
            && tweetsFilter === TWEETS_VIEW_HIDDEN
            && (e.owner.id === USER_ID && e.owner.id === payload.id)) {
            const show = document.createElement('div');
            show.setAttribute('class', 'row');
            show.innerHTML = `<button class="btn" onclick="showTweet(${e.id});">Show</button>`;
            header.appendChild(show);
        }

        card.appendChild(header);

        const body = document.createElement('div');
        body.setAttribute('class', 'card-body card-body-tweet');

        const raw = document.createElement('div');
        raw.setAttribute('class', 'row');
        raw.innerHTML = e.raw;
        body.appendChild(raw);

        card.appendChild(body);

        root.appendChild(card);
    });
}

window.hideTweet = function(id) {
    const usp = new URLSearchParams();
    usp.append('hidden', 1);

    patchTweet(Number(id), usp);

    for (let i = 0; i < tweetsList.length; ++i) {
        if (tweetsList[i].id === Number(id)) {
            tweetsList[i].hidden = true;
            break;
        }
    }

    const root = document.getElementById('tweets-root');
    const card = document.getElementById(`tweet-card-${id}`);
    root.removeChild(card);
};

window.showTweet = function(id) {
    const usp = new URLSearchParams();
    usp.append('hidden', 0);

    patchTweet(Number(id), usp);

    for (let i = 0; i < tweetsList.length; ++i) {
        if (tweetsList[i].id === Number(id)) {
            tweetsList[i].hidden = false;
            break;
        }
    }

    const root = document.getElementById('tweets-root');
    const card = document.getElementById(`tweet-card-${id}`);
    root.removeChild(card);
};

$('#post-new').on('click', () => { visitPage(Routing.generate('post_new')); });

$('#refresh-tweets').on('click', () => { updateTweetsList(); });

$('#sidebar-toggle').on('click', () => {
    if (statusOpen) {
        $('.sidebar').attr('hidden', 'hidden');
    } else {
        $('.sidebar').removeAttr('hidden');
    }

    statusOpen = !statusOpen;
});

$('#tweets-filter').on('change', () => { updateTweetsRoot(); });
