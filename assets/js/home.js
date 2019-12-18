import 'dist/js/app.js';

import {
    HandleFetch,
    getToken,
    handleLinks,
    removePayload,
    visitPage,
} from './_utils';

$(document).ready(function() {
    handleLinks();
    $('#home-link').hide();
});
